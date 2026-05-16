<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=320, initial-scale=1.0">
    <title>Invoice - {{ $transaction->invoice_number }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: {{ ($pdfMode ?? false) ? "'Courier New', monospace" : "system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif" }};
            background: {{ ($pdfMode ?? false) ? '#fff' : '#f3f4f6' }};
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: {{ ($pdfMode ?? false) ? '0' : '20px' }};
            min-height: 100vh;
        }
        @if ($pdfMode ?? false)
        .receipt { box-shadow: none; border-radius: 0; max-width: 100%; }
        .receipt-body { padding: 16px 20px; }
        .actions, .watermark { display: none !important; }
        @endif
        .receipt {
            width: 100%;
            max-width: 400px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .receipt-header {
            background: {{ ($pdfMode ?? false) ? '#2563eb' : 'linear-gradient(135deg, #2563eb, #4f46e5)' }};
            color: #fff;
            padding: 24px 24px 20px;
            text-align: center;
        }
        .receipt-header h1 { font-size: 20px; font-weight: 700; letter-spacing: -0.5px; }
        .receipt-header p { font-size: 12px; opacity: 0.8; margin-top: 2px; }
        .receipt-body { padding: 20px 24px; }
        .invoice-meta {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px dashed #e5e7eb;
        }
        .invoice-meta .label { color: #9ca3af; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        .invoice-meta .value { color: #111827; font-weight: 600; margin-top: 2px; }
        table.items { width: 100%; border-collapse: collapse; font-size: 13px; }
        table.items th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #9ca3af;
            padding: 6px 0 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        table.items th:last-child, table.items td:last-child { text-align: right; }
        table.items td { padding: 8px 0; border-bottom: 1px solid #f3f4f6; color: #374151; }
        table.items .item-name { font-weight: 500; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        table.items .item-qty { color: #6b7280; text-align: center; width: 32px; }
        table.items .item-price { text-align: right; font-weight: 500; white-space: nowrap; }
        .divider-dash { border-top: 1px dashed #e5e7eb; margin: 12px 0; }
        table.totals { width: 100%; border-collapse: collapse; font-size: 13px; }
        table.totals td { padding: 4px 0; }
        table.totals .label { color: #6b7280; }
        table.totals .value { text-align: right; font-weight: 500; }
        table.totals .grand td { padding-top: 8px; font-size: 16px; font-weight: 700; color: #111827; }
        table.totals .grand td:last-child { color: #2563eb; }
        table.totals .change td:last-child { color: #059669; }
        .receipt-footer {
            text-align: center;
            padding: 16px 24px 20px;
            border-top: 1px dashed #e5e7eb;
            color: #9ca3af;
            font-size: 12px;
            line-height: 1.6;
        }
        .receipt-footer .thankyou { font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 4px; }
        .actions {
            display: flex;
            gap: 8px;
            margin-top: 16px;
            max-width: 400px;
            width: 100%;
            flex-wrap: wrap;
        }
        .actions a, .actions button {
            flex: 1;
            min-width: 100px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        .btn-print { background: #111827; color: #fff; }
        .btn-print:hover { background: #1f2937; }
        .btn-pdf { background: #dc2626; color: #fff; }
        .btn-pdf:hover { background: #b91c1c; }
        .btn-wa { background: #25d366; color: #fff; }
        .btn-wa:hover { background: #1da851; }
        .btn-back { background: #f3f4f6; color: #374151; }
        .btn-back:hover { background: #e5e7eb; }
        .watermark { font-size: 10px; color: #d1d5db; text-align: center; margin-top: 12px; }
        @media print {
            body { background: #fff; padding: 0; }
            .receipt { box-shadow: none; border-radius: 0; max-width: 80mm; }
            .receipt-header { background: #2563eb !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .actions, .watermark { display: none; }
        }
        @media (max-width: 480px) {
            body { padding: 8px; background: #fff; }
            .receipt { box-shadow: none; border-radius: 0; }
            .actions a, .actions button { min-width: 0; font-size: 12px; padding: 8px 12px; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h1>{{ $storeName }}</h1>
            <p>{{ __('Payment Receipt') }}</p>
        </div>
        <div class="receipt-body">
            <div class="invoice-meta">
                <div>
                    <div class="label">{{ __('Invoice') }}</div>
                    <div class="value">{{ $transaction->invoice_number }}</div>
                </div>
                <div style="text-align:right">
                    <div class="label">{{ __('Date') }}</div>
                    <div class="value">{{ $transaction->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            <table class="items">
                <thead>
                    <tr>
                        <th>{{ __('Item') }}</th>
                        <th style="text-align:center">{{ __('Qty') }}</th>
                        <th>{{ __('Subtotal') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->details as $detail)
                    <tr>
                        <td class="item-name">{{ $detail->product->name }}</td>
                        <td style="text-align:center">{{ $detail->quantity }}</td>
                        <td class="item-price">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="divider-dash"></div>

            <table class="totals">
                <tr>
                    <td class="label">{{ __('Subtotal') }}</td>
                    <td class="value">Rp{{ number_format($transaction->total_price + $transaction->discount_from_points, 0, ',', '.') }}</td>
                </tr>
                @if ($transaction->discount_from_points > 0)
                <tr>
                    <td class="label">{{ __('Points Discount') }}</td>
                    <td class="value" style="color:#059669">-Rp{{ number_format($transaction->discount_from_points, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="grand">
                    <td>{{ __('Total') }}</td>
                    <td>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">{{ __('Cash') }}</td>
                    <td class="value">Rp{{ number_format($transaction->amount_paid, 0, ',', '.') }}</td>
                </tr>
                <tr class="change">
                    <td class="label">{{ __('Change') }}</td>
                    <td class="value">Rp{{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
                </tr>
            </table>

            <div class="divider-dash"></div>

            <div style="font-size:12px;color:#6b7280;line-height:1.8">
                <strong>{{ __('Cashier') }}:</strong> {{ $transaction->cashier->name ?? '-' }}<br>
                @if ($transaction->details->count() > 0)
                <strong>{{ __('Items') }}:</strong> {{ $transaction->details->sum('quantity') }} {{ __('items') }}<br>
                @endif
                @if ($transaction->member)
                <strong>{{ __('Member') }}:</strong> {{ $transaction->member->name }}
                @if ($transaction->points_earned > 0)
                &middot; +{{ $transaction->points_earned }} {{ __('Points') }}
                @endif
                @endif
            </div>
        </div>

        <div class="receipt-footer">
            <div class="thankyou">{{ __('Thank You') }}</div>
            <div>{{ __('Items purchased cannot be returned') }}</div>
            <div style="margin-top:4px">{{ now()->format('d M Y H:i:s') }}</div>
        </div>
    </div>

    <div class="actions no-print">
        <button onclick="window.print()" class="btn-print"><i class="bi bi-printer"></i> {{ __('Print') }}</button>
        <a href="{{ route('transactions.receipt-pdf', $transaction) }}" class="btn-pdf"><i class="bi bi-filetype-pdf"></i> {{ __('Download PDF') }}</a>
        <a href="https://wa.me/?text={{ urlencode(__('Thank you for shopping at') . ' ' . $storeName . '!' . "\n" . __('Invoice') . ': ' . $transaction->invoice_number . "\n" . __('Total') . ': Rp' . number_format($transaction->total_price, 0, ',', '.') . "\n" . __('Date') . ': ' . $transaction->created_at->format('d/m/Y H:i')) }}" target="_blank" class="btn-wa"><i class="bi bi-whatsapp"></i> {{ __('Share WhatsApp') }}</a>
        <button onclick="window.close()" class="btn-back"><i class="bi bi-x-lg"></i> {{ __('Close') }}</button>
    </div>

    <div class="watermark">{{ __('Created by') }} {{ $storeName }}</div>

    @if (!request()->has('pdf'))
    <script>
        window.onload = function () {
            if (!window.location.search.includes('noprint')) {
                setTimeout(function () { window.print(); }, 500);
            }
        };
    </script>
    @endif
</body>
</html>
