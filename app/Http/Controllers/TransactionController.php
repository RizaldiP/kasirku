<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Product;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function pos()
    {
        $products = Product::orderBy('name')->get();
        $qrisExists = file_exists(public_path('storage/qris.png'));
        $pointsEarnPerAmount = (int) \App\Models\Setting::get('points_earn_per_amount', 10000);
        $pointsRedeemPerDiscount = (int) \App\Models\Setting::get('points_redeem_per_discount', 100);
        $pointsDiscountPerUnit = (int) \App\Models\Setting::get('points_discount_per_unit', 2000);
        $storeName = \App\Models\Setting::get('store_name', 'Kasirku');
        return view('transactions.pos', compact(
            'products', 'qrisExists', 'storeName',
            'pointsEarnPerAmount', 'pointsRedeemPerDiscount', 'pointsDiscountPerUnit',
        ));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('barcode', 'like', "%{$query}%")
            ->orderBy('name')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'barcode' => $p->barcode,
                    'price' => (float) $p->selling_price,
                    'stock' => $p->stock,
                    'image' => $p->image ? asset('storage/' . $p->image) : null,
                ];
            });

        return response()->json($products);
    }

    public function registerMember(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:members,phone',
        ]);

        $member = Member::create($validated);

        return response()->json([
            'success' => true,
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'phone' => $member->phone,
                'points' => $member->points,
            ],
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'amount_paid' => 'required|numeric|min:0',
            'member_id' => 'nullable|exists:members,id',
            'points_redeemed' => 'nullable|integer|min:0',
        ]);

        $items = $request->items;
        $totalPrice = 0;

        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$product->name} tidak mencukupi (tersedia: {$product->stock})",
                    ], 422);
                }
                $totalPrice += $product->selling_price * $item['quantity'];
            }

            $amountPaid = (float) $request->amount_paid;
            $discountFromPoints = 0;
            $pointsRedeemed = 0;

            $redeemPerDiscount = Member::redeemPerDiscount();
            $discountPerUnit = Member::discountPerUnit();

            if ($request->member_id && $request->points_redeemed > 0) {
                $member = Member::findOrFail($request->member_id);
                $pointsRedeemed = (int) $request->points_redeemed;
                $maxRedeemable = (int) (floor($member->points / $redeemPerDiscount) * $redeemPerDiscount);
                $pointsRedeemed = min($pointsRedeemed, $maxRedeemable);
                $redeemUnits = (int) ($pointsRedeemed / $redeemPerDiscount);
                $discountFromPoints = $redeemUnits * $discountPerUnit;
            }

            $finalTotal = $totalPrice - $discountFromPoints;
            $changeAmount = max(0, $amountPaid - $finalTotal);

            $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

            $transactionData = [
                'invoice_number' => $invoiceNumber,
                'total_price' => $finalTotal,
                'amount_paid' => $amountPaid,
                'change_amount' => $changeAmount,
                'cashier_id' => auth()->id(),
                'member_id' => $request->member_id,
                'points_earned' => 0,
                'points_redeemed' => $pointsRedeemed,
                'discount_from_points' => $discountFromPoints,
            ];

            $transaction = Transaction::create($transactionData);

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->selling_price * $item['quantity'];

                $transaction->details()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            // Handle member points
            if ($request->member_id) {
                $member = Member::findOrFail($request->member_id);
                $pointsEarned = (int) floor($totalPrice / Member::earnPerAmount());

                if ($pointsEarned > 0) {
                    $member->pointsLog()->create([
                        'transaction_id' => $transaction->id,
                        'points' => $pointsEarned,
                        'type' => 'earn',
                        'description' => 'Poin dari transaksi #' . $invoiceNumber,
                    ]);
                }

                if ($pointsRedeemed > 0) {
                    $member->pointsLog()->create([
                        'transaction_id' => $transaction->id,
                        'points' => -$pointsRedeemed,
                        'type' => 'redeem',
                        'description' => 'Tukar poin diskon transaksi #' . $invoiceNumber,
                    ]);
                }

                $member->increment('points', $pointsEarned - $pointsRedeemed);
                $member->increment('total_spent', $totalPrice);

                $transaction->update(['points_earned' => $pointsEarned]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'transaction' => $transaction->load('details.product', 'member'),
                'redirect' => route('transactions.receipt', $transaction),
                'message' => 'Transaksi berhasil!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $query = Transaction::with('cashier', 'details.product', 'member');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%");
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $totalRevenue = (float) $query->sum('total_price');
        $totalCount = (clone $query)->count();
        $average = $totalCount > 0 ? $totalRevenue / $totalCount : 0;
        $todayCount = Transaction::whereDate('created_at', today())->count();

        $transactions = $query->latest()->paginate(10);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('transactions._table', compact('transactions'))->render(),
                'summary' => [
                    'revenue' => number_format($totalRevenue, 0, ',', '.'),
                    'count' => $totalCount,
                    'average' => number_format($average, 0, ',', '.'),
                    'today' => $todayCount,
                ],
            ]);
        }

        return view('transactions.index', compact('transactions', 'totalRevenue', 'totalCount', 'average', 'todayCount'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('cashier', 'details.product', 'member');
        $storeName = \App\Models\Setting::get('store_name', 'Kasirku');
        return view('transactions.show', compact('transaction', 'storeName'));
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load('cashier', 'details.product', 'member');
        $storeName = \App\Models\Setting::get('store_name', 'Kasirku');
        return view('transactions.receipt', compact('transaction', 'storeName'));
    }

    public function receiptPdf(Transaction $transaction)
    {
        $transaction->load('cashier', 'details.product', 'member');
        $storeName = \App\Models\Setting::get('store_name', 'Kasirku');
        $html = view('transactions.receipt', compact('transaction', 'storeName') + ['pdfMode' => true])->render();
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper([0, 0, 240, 700]);
        return $pdf->download('invoice-' . $transaction->invoice_number . '.pdf');
    }

    public function export(Request $request)
    {
        $this->applyDateFilter($request, $query = Transaction::with('cashier', 'member'));
        $transactions = $query->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Transaksi');

        $dateLabel = '';
        if ($request->filled('date_from') || $request->filled('date_to')) {
            $dateLabel = ' (' . ($request->date_from ?? 'awal') . ' s/d ' . ($request->date_to ?? 'akhir') . ')';
        }
        $sheet->setCellValue('A1', 'Laporan Transaksi' . $dateLabel);
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension('1')->setRowHeight(30);

        $headers = ['Invoice', 'Tanggal', 'Kasir', 'Item', 'Subtotal', 'Diskon Poin',
                     'Total', 'Bayar', 'Kembali', 'Member', 'Poin Diperoleh', 'Poin Ditukar'];
        $col = 'A';
        foreach ($headers as $i => $h) {
            $cell = $col . '2';
            $sheet->setCellValue($cell, $h);
            $col++;
        }

        $headerStyle = $sheet->getStyle('A2:L2');
        $headerStyle->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
        $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1F2937'));
        $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension('2')->setRowHeight(22);

        $row = 3;
        $totalSubtotal = 0;
        $totalDiscount = 0;
        $totalGrand = 0;
        foreach ($transactions as $t) {
            $subtotal = $t->total_price + (int) $t->discount_from_points;
            $itemCount = $t->details()->sum('quantity');
            $totalSubtotal += $subtotal;
            $totalDiscount += (int) $t->discount_from_points;
            $totalGrand += (int) $t->total_price;

            $sheet->setCellValue('A' . $row, $t->invoice_number);
            $sheet->setCellValue('B' . $row, $t->created_at->format('d/m/Y H:i'));
            $sheet->setCellValue('C' . $row, $t->cashier->name ?? '-');
            $sheet->setCellValue('D' . $row, $itemCount);
            $sheet->setCellValue('E' . $row, $subtotal);
            $sheet->setCellValue('F' . $row, (int) $t->discount_from_points);
            $sheet->setCellValue('G' . $row, (int) $t->total_price);
            $sheet->setCellValue('H' . $row, (int) $t->amount_paid);
            $sheet->setCellValue('I' . $row, (int) $t->change_amount);
            $sheet->setCellValue('J' . $row, $t->member->name ?? '-');
            $sheet->setCellValue('K' . $row, (int) $t->points_earned);
            $sheet->setCellValue('L' . $row, (int) $t->points_redeemed);

            $sheet->getStyle('A' . $row . ':L' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;
        }

        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->mergeCells('A' . $row . ':D' . $row);
        $sheet->setCellValue('E' . $row, $totalSubtotal);
        $sheet->setCellValue('F' . $row, $totalDiscount);
        $sheet->setCellValue('G' . $row, $totalGrand);
        $sheet->setCellValue('H' . $row, $transactions->sum('amount_paid'));
        $sheet->setCellValue('I' . $row, $transactions->sum('change_amount'));

        $totalRow = $row;
        $sheet->getStyle('A' . $totalRow . ':L' . $totalRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $totalRow . ':L' . $totalRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)->setStartColor(new \PhpOffice\PhpSpreadsheet\Style\Color('E5E7EB'));
        $sheet->getStyle('A' . $totalRow . ':L' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $lastRow = $row;
        $styleArray = [
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '9CA3AF']],
            ],
        ];
        $sheet->getStyle('A2:L' . $lastRow)->applyFromArray($styleArray);

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);

        foreach (range('E', 'I') as $c) {
            $sheet->getStyle($c . '3:' . $c . $lastRow)
                ->getNumberFormat()->setFormatCode('#,##0');
        }
        foreach (['K', 'L'] as $c) {
            $sheet->getStyle($c . '3:' . $c . $lastRow)
                ->getNumberFormat()->setFormatCode('#,##0');
        }

        $filename = 'transaksi-' . now()->format('Y-m-d-His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $this->applyDateFilter($request, $query = Transaction::with('cashier', 'member'));
        $transactions = $query->latest()->get();

        $totalRevenue = $transactions->sum('total_price');
        $totalCount = $transactions->count();
        $totalDiscount = $transactions->sum('discount_from_points');

        $html = view('transactions.export-pdf', compact('transactions', 'totalRevenue', 'totalCount', 'totalDiscount'))->render();
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('transaksi-' . now()->format('Y-m-d-His') . '.pdf');
    }

    private function applyDateFilter(Request $request, $query)
    {
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        return $query;
    }
}
