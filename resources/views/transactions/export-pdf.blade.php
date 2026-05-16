<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('Transaction Report') }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9pt; color: #1f2937; }
        h2 { text-align: center; margin-bottom: 4px; font-size: 14pt; }
        .subtitle { text-align: center; color: #6b7280; font-size: 8pt; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th {
            background: #1f2937; color: #fff; padding: 6px 4px;
            font-size: 8pt; text-align: center; font-weight: 600;
        }
        td { padding: 4px; border: 1px solid #d1d5db; text-align: center; font-size: 8pt; }
        tr:nth-child(even) { background: #f9fafb; }
        .total-row td { background: #e5e7eb; font-weight: bold; }
        .text-right { text-align: right; padding-right: 8px; }
        .summary {
            display: flex; justify-content: space-between; margin-top: 14px;
            padding: 8px 12px; background: #f3f4f6; border-radius: 4px; font-size: 9pt;
        }
        .summary div span { font-weight: bold; }
    </style>
</head>
<body>
    <h2>{{ __('Transaction Report') }}</h2>
    <p class="subtitle">{{ __('Period') }}: {{ request('date_from', __('Start')) }} s/d {{ request('date_to', __('End')) }} | {{ __('Printed') }}: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>{{ __('No') }}</th>
                <th>{{ __('Invoice') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Cashier') }}</th>
                <th>{{ __('Items') }}</th>
                <th>{{ __('Subtotal') }}</th>
                <th>{{ __('Points Discount') }}</th>
                <th>{{ __('Total') }}</th>
                <th>{{ __('Paid') }}</th>
                <th>{{ __('Change') }}</th>
                <th>{{ __('Member') }}</th>
                <th>{{ __('Points Earned') }}</th>
                <th>{{ __('Points Redeemed') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $i => $t)
            @php
                $subtotal = $t->total_price + (int) $t->discount_from_points;
                $itemCount = $t->details()->sum('quantity');
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $t->invoice_number }}</td>
                <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $t->cashier->name ?? '-' }}</td>
                <td>{{ $itemCount }}</td>
                <td class="text-right">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                <td class="text-right">{{ $t->discount_from_points > 0 ? 'Rp' . number_format($t->discount_from_points, 0, ',', '.') : '-' }}</td>
                <td class="text-right">Rp{{ number_format($t->total_price, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($t->amount_paid, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($t->change_amount, 0, ',', '.') }}</td>
                <td>{{ $t->member->name ?? '-' }}</td>
                <td>{{ (int) $t->points_earned }}</td>
                <td>{{ (int) $t->points_redeemed }}</td>
            </tr>
            @empty
            <tr><td colspan="13" style="text-align:center;padding:20px;color:#9ca3af;">{{ __('No transactions') }}</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5">{{ __('TOTAL') }}</td>
                <td class="text-right">Rp{{ number_format($totalRevenue + $totalDiscount, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($totalDiscount, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($transactions->sum('amount_paid'), 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($transactions->sum('change_amount'), 0, ',', '.') }}</td>
                <td></td>
                <td>{{ $transactions->sum('points_earned') }}</td>
                <td>{{ $transactions->sum('points_redeemed') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <div>{{ __('Total Transactions') }}: <span>{{ $totalCount }}</span></div>
        <div>{{ __('Total Revenue') }}: <span>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</span></div>
        <div>{{ __('Total Points Discount') }}: <span>Rp{{ number_format($totalDiscount, 0, ',', '.') }}</span></div>
    </div>
</body>
</html>