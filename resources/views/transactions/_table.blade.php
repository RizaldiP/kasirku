<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="table-header-gradient text-gray-600 dark:text-slate-400">
                    <th class="px-4 py-3 text-left font-medium">{{ __('Invoice') }}</th>
                    <th class="px-4 py-3 text-left font-medium hidden md:table-cell">{{ __('Cashier') }}</th>
                    <th class="px-4 py-3 text-left font-medium hidden sm:table-cell">{{ __('Items') }}</th>
                    <th class="px-4 py-3 text-left font-medium hidden lg:table-cell">{{ __('Member') }}</th>
                    <th class="px-4 py-3 text-left font-medium">{{ __('Total') }}</th>
                    <th class="px-4 py-3 text-left font-medium hidden sm:table-cell">{{ __('Paid') }}</th>
                    <th class="px-4 py-3 text-left font-medium hidden sm:table-cell">{{ __('Change') }}</th>
                    <th class="px-4 py-3 text-left font-medium hidden md:table-cell">{{ __('Date') }}</th>
                    <th class="px-4 py-3 text-left font-medium">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($transactions as $t)
                    <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-blue-50/30 transition-all">
                        <td data-label="{{ __('Invoice') }}" class="px-4 py-3 font-mono text-sm">{{ $t->invoice_number }}</td>
                        <td data-label="{{ __('Cashier') }}" class="px-4 py-3 hidden md:table-cell dark:text-slate-300">{{ $t->cashier->name ?? '-' }}</td>
                        <td data-label="{{ __('Items') }}" class="px-4 py-3 hidden sm:table-cell">{{ $t->details->sum('quantity') }}</td>
                        <td data-label="{{ __('Member') }}" class="px-4 py-3 hidden lg:table-cell">
                            @if ($t->member)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300">
                                    <i class="bi bi-person-badge"></i> {{ $t->member->name }}
                                </span>
                            @else
                                <span class="text-gray-300 dark:text-slate-600 text-xs">-</span>
                            @endif
                        </td>
                        <td data-label="{{ __('Total') }}" class="px-4 py-3 font-semibold dark:text-slate-200">Rp{{ number_format($t->total_price, 0, ',', '.') }}</td>
                        <td data-label="{{ __('Paid') }}" class="px-4 py-3 hidden sm:table-cell">Rp{{ number_format($t->amount_paid, 0, ',', '.') }}</td>
                        <td data-label="{{ __('Change') }}" class="px-4 py-3 hidden sm:table-cell text-emerald-600 dark:text-emerald-400 font-medium">Rp{{ number_format($t->change_amount, 0, ',', '.') }}</td>
                        <td data-label="{{ __('Date') }}" class="px-4 py-3 hidden md:table-cell text-gray-500 dark:text-slate-400 text-sm">{{ $t->created_at->format('d M Y H:i') }}</td>
                        <td data-label="{{ __('Action') }}" class="px-4 py-3">
                            <div class="flex gap-1">
                                <a href="{{ route('transactions.show', $t) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-500 dark:text-slate-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-200 dark:hover:border-indigo-700 transition-all" title="{{ __('Detail Transaction') }}"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('transactions.receipt', $t) }}" target="_blank" class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-500 dark:text-slate-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 hover:text-emerald-600 dark:hover:text-emerald-400 hover:border-emerald-200 dark:hover:border-emerald-700 transition-all" title="{{ __('Print') }}"><i class="bi bi-printer"></i></a>
                                <a href="{{ route('transactions.receipt-pdf', $t) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-500 dark:text-slate-400 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 hover:border-red-200 dark:hover:border-red-700 transition-all" title="{{ __('Download PDF') }}"><i class="bi bi-filetype-pdf"></i></a>
                                <a href="https://wa.me/?text={{ urlencode(__('Invoice') . ': ' . $t->invoice_number . ' - ' . __('Total') . ': Rp' . number_format($t->total_price, 0, ',', '.')) }}" target="_blank" class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 dark:border-slate-600 text-gray-500 dark:text-slate-400 hover:bg-green-50 dark:hover:bg-green-900/30 hover:text-green-600 dark:hover:text-green-400 hover:border-green-200 dark:hover:border-green-700 transition-all" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="px-4 py-8 text-center text-gray-400 dark:text-slate-500">{{ __('No transactions') }}.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4 pagination-color">{{ $transactions->links() }}</div>
