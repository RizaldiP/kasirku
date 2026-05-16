@extends('layouts.app')

@section('title', __('Detail Transaction'))

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-200">{{ __('Detail Transaction') }}</h1>
        <div class="flex gap-2 flex-wrap w-full sm:w-auto">
            <a href="{{ route('transactions.receipt', $transaction) }}" target="_blank" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 text-white font-medium rounded-xl gradient-primary btn-scale">
                <i class="bi bi-printer"></i> {{ __('Print Receipt') }}
            </a>
            <a href="{{ route('transactions.receipt-pdf', $transaction) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 text-white font-medium rounded-xl gradient-danger btn-scale">
                <i class="bi bi-filetype-pdf"></i> {{ __('Download PDF') }}
            </a>
            <a href="https://wa.me/?text={{ urlencode(__('Thank you for shopping at Kasirku!') . "\n" . __('Invoice') . ': ' . $transaction->invoice_number . "\n" . __('Total') . ': Rp' . number_format($transaction->total_price, 0, ',', '.') . "\n" . __('Date') . ': ' . $transaction->created_at->format('d/m/Y H:i')) }}" target="_blank" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 text-white font-medium rounded-xl btn-scale" style="background:#25d366">
                <i class="bi bi-whatsapp"></i> {{ __('Share WhatsApp') }}
            </a>
            <a href="{{ route('transactions.pos') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 text-white font-medium rounded-xl gradient-success btn-scale">
                <i class="bi bi-plus-lg"></i> {{ __('New Transaction') }}
            </a>
            <a href="{{ route('transactions.index') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 font-medium rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600 btn-scale">
                <i class="bi bi-arrow-left"></i> {{ __('Back') }}
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 card-hover">
        <div class="p-5">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-gray-500 dark:text-slate-400 text-sm">{{ __('Invoice') }}</p>
                    <p class="font-bold font-mono dark:text-slate-200">{{ $transaction->invoice_number }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-slate-400 text-sm">{{ __('Cashier') }}</p>
                    <p class="font-bold dark:text-slate-200">{{ $transaction->cashier->name ?? '-' }}</p>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <p class="text-gray-500 dark:text-slate-400 text-sm">{{ __('Date') }}</p>
                    <p class="font-bold dark:text-slate-200">{{ $transaction->created_at->format('d M Y H:i:s') }}</p>
                </div>
                @if ($transaction->member)
                <div>
                    <p class="text-gray-500 dark:text-slate-400 text-sm">{{ __('Member') }}</p>
                    <p class="font-bold dark:text-slate-200 flex items-center gap-1"><i class="bi bi-person-badge text-purple-500"></i> {{ $transaction->member->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-slate-400 text-sm">{{ __('Point') }}</p>
                    <p class="font-bold">
                        @if ($transaction->points_earned > 0)
                        <span class="text-amber-600 dark:text-amber-400">+{{ $transaction->points_earned }} {{ __('Points Earned') }}</span>
                        @endif
                        @if ($transaction->points_redeemed > 0)
                        <span class="text-red-600 dark:text-red-400"> / -{{ $transaction->points_redeemed }} {{ __('Points Redeemed') }}</span>
                        @endif
                        @if (!$transaction->points_earned && !$transaction->points_redeemed)
                        <span class="text-gray-400 dark:text-slate-500">-</span>
                        @endif
                    </p>
                </div>
                @if ($transaction->discount_from_points > 0)
                <div>
                    <p class="text-gray-500 dark:text-slate-400 text-sm">{{ __('Points Discount') }}</p>
                    <p class="font-bold text-emerald-600 dark:text-emerald-400">-Rp{{ number_format($transaction->discount_from_points, 0, ',', '.') }}</p>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="table-header-gradient text-gray-600 dark:text-slate-300">
                        <th class="px-4 py-3 text-left font-medium">{{ __('Product') }}</th>
                        <th class="px-4 py-3 text-left font-medium hidden sm:table-cell">{{ __('Price') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Qty') }}</th>
                        <th class="px-4 py-3 text-right font-medium">{{ __('Subtotal') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @foreach ($transaction->details as $detail)
                        <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-blue-50/30 dark:hover:from-slate-800 dark:hover:to-slate-700 transition-all">
                            <td data-label="{{ __('Product') }}" class="px-4 py-3 dark:text-slate-200">{{ $detail->product->name }}</td>
                            <td data-label="{{ __('Price') }}" class="px-4 py-3 hidden sm:table-cell">Rp{{ number_format($detail->product->selling_price, 0, ',', '.') }}</td>
                            <td data-label="{{ __('Qty') }}" class="px-4 py-3">{{ $detail->quantity }}</td>
                            <td data-label="{{ __('Subtotal') }}" class="px-4 py-3 text-right">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-slate-800 font-semibold">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right dark:text-slate-300">{{ __('Total') }}</td>
                        <td class="px-4 py-3 text-right text-lg dark:text-slate-200">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="font-normal">
                        <td colspan="3" class="px-4 py-2 text-right text-gray-600 dark:text-slate-400">{{ __('Paid') }}</td>
                        <td class="px-4 py-2 text-right dark:text-slate-200">Rp{{ number_format($transaction->amount_paid, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="font-normal">
                        <td colspan="3" class="px-4 py-2 text-right text-gray-600 dark:text-slate-400">{{ __('Change') }}</td>
                        <td class="px-4 py-2 text-right text-emerald-600 dark:text-emerald-400 font-semibold">Rp{{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
