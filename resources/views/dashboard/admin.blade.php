@extends('layouts.app')

@section('title', __('Dashboard'))

@section('content')
    <div class="mb-6 p-6 rounded-2xl gradient-primary shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">{{ __('Dashboard') }}</h1>
                <p class="text-blue-200 mt-1 text-sm">{{ __('Welcome') }}, {{ auth()->user()->name }}!</p>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-white/80 text-sm">
                <i class="bi bi-calendar3"></i>
                <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="card-hover bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="gradient-primary p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">{{ __('Total Revenue') }}</p>
                        <p class="text-2xl font-bold text-white mt-1">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="bi bi-graph-up-arrow text-2xl text-white"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1 text-blue-200 text-xs">
                    <i class="bi bi-arrow-up-circle-fill"></i>
                    <span>{{ __('Total Revenue') }}</span>
                </div>
            </div>
        </div>

        <div class="card-hover bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="gradient-success p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-emerald-100 text-xs font-medium uppercase tracking-wider">{{ __('Total Transactions') }}</p>
                        <p class="text-2xl font-bold text-white mt-1">{{ $totalTransactions }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="bi bi-cart-check text-2xl text-white"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1 text-emerald-200 text-xs">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ __('Total Transactions') }}</span>
                </div>
            </div>
        </div>

        <div class="card-hover bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="gradient-purple p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-xs font-medium uppercase tracking-wider">{{ __('Total') }} {{ __('Products') }}</p>
                        <p class="text-2xl font-bold text-white mt-1">{{ $totalProducts }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="bi bi-box-seam text-2xl text-white"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1 text-purple-200 text-xs">
                    <i class="bi bi-cube-fill"></i>
                    <span>{{ __('Products') }} {{ __('Stock available') }}</span>
                </div>
            </div>
        </div>

        <div class="card-hover bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="gradient-warning p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-amber-100 text-xs font-medium uppercase tracking-wider">{{ __('Total') }} {{ __('Users') }}</p>
                        <p class="text-2xl font-bold text-white mt-1">{{ $totalUsers }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="bi bi-people text-2xl text-white"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1 text-amber-200 text-xs">
                    <i class="bi bi-person-check-fill"></i>
                    <span>{{ __('Registered Users') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 gradient-primary">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bi bi-bar-chart-fill text-lg text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-white">{{ __('Sales Last 7 Days') }}</h3>
                        <p class="text-blue-200 text-xs">{{ __('Daily revenue chart') }}</p>
                    </div>
                </div>
            </div>
            <div class="p-5">
                <canvas id="dailyChart" style="max-height:250px;min-height:150px"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 gradient-success">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bi bi-graph-up text-lg text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-white">{{ __('Monthly Sales') }}</h3>
                        <p class="text-emerald-200 text-xs">{{ __('Monthly revenue chart') }}</p>
                    </div>
                </div>
            </div>
            <div class="p-5">
                <canvas id="monthlyChart" style="max-height:250px;min-height:150px"></canvas>
            </div>
        </div>
    </div>

    @if ($lowStock->count() > 0)
    <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover">
        <div class="px-6 py-4" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bi bi-exclamation-triangle text-lg text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-white">{{ __('Low Stock') }}</h3>
                        <p class="text-yellow-100 text-xs">{{ $lowStock->count() }} {{ __('Products') }} {{ __('need restock') }}</p>
                    </div>
                </div>
                <a href="{{ route('products.index') }}" class="btn-scale inline-flex items-center gap-1 text-sm text-white/80 hover:text-white font-medium bg-white/10 px-3 py-1.5 rounded-lg">
                    {{ __('Manage') }} <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach ($lowStock as $item)
            <div class="flex items-center justify-between px-6 py-3 hover:bg-amber-50/50 transition-colors">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center flex-shrink-0">
                        {{ strtoupper(substr($item->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $item->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $item->category ?? __('No Category') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    @if ($item->stock <= 0)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            <i class="bi bi-x-circle-fill"></i> {{ __('Out of Stock') }}
                        </span>
                    @elseif ($item->stock <= 5)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ $item->stock }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                            <i class="bi bi-exclamation-triangle-fill"></i> {{ $item->stock }}
                        </span>
                    @endif
                    <div class="w-1 h-8 rounded-full {{ $item->stock <= 0 ? 'bg-red-500' : ($item->stock <= 5 ? 'bg-red-400' : 'bg-amber-400') }}"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover">
        <div class="px-6 py-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center shadow-md">
                <i class="bi bi-check-circle-fill text-2xl text-white"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 dark:text-slate-200">{{ __('Stock Safe') }}</h3>
                <p class="text-sm text-gray-500 dark:text-slate-400">{{ __('All products have sufficient stock.') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-lg flex items-center justify-center">
                        <i class="bi bi-receipt text-lg text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-slate-200">{{ __('Last Transactions') }}</h3>
                        <p class="text-gray-500 dark:text-slate-400 text-xs">{{ count($recentTransactions) }} {{ __('transactions') }}</p>
                    </div>
                </div>
                <a href="{{ route('transactions.index') }}" class="btn-scale inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                    {{ __('See All') }} <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-blue-50 dark:from-slate-700 dark:to-slate-800 text-gray-600 dark:text-slate-300">
                            <th class="px-4 py-3 text-left font-semibold">{{ __('Invoice') }}</th>
                            <th class="px-4 py-3 text-left font-semibold">{{ __('Cashier') }}</th>
                            <th class="px-4 py-3 text-left font-semibold">{{ __('Total') }}</th>
                            <th class="px-4 py-3 text-left font-semibold">{{ __('Time') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($recentTransactions as $t)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-4 py-3 font-mono text-sm text-blue-700">#{{ $t->invoice_number }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-400 to-purple-400 flex items-center justify-center text-white text-xs font-bold">
                                            {{ strtoupper(substr($t->cashier->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span>{{ $t->cashier->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-semibold text-emerald-600">Rp{{ number_format($t->total_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-gray-500 text-xs">{{ $t->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-400 rounded-lg flex items-center justify-center">
                        <i class="bi bi-trophy text-lg text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-slate-200">{{ __('Top Products') }}</h3>
                        <p class="text-gray-500 dark:text-slate-400 text-xs">{{ count($topProducts) }} {{ __('top products') }}</p>
                    </div>
                </div>
                <a href="{{ route('products.index') }}" class="btn-scale inline-flex items-center gap-1 text-sm text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300 font-medium">
                    {{ __('See All') }} <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-amber-50 dark:from-slate-700 dark:to-slate-800 text-gray-600 dark:text-slate-300">
                            <th class="px-4 py-3 text-left font-semibold">{{ __('Product') }}</th>
                            <th class="px-4 py-3 text-left font-semibold">{{ __('Sold') }}</th>
                            <th class="px-4 py-3 text-left font-semibold">{{ __('Total') }}</th>
                            <th class="px-4 py-3 text-left font-semibold">{{ __('Rank') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($topProducts as $idx => $item)
                            <tr class="hover:bg-amber-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center text-amber-700 font-bold text-sm">
                                            {{ strtoupper(substr($item['product']->name ?? 'N/A', 0, 1)) }}
                                        </div>
                                        <span class="font-medium">{{ $item['product']->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        <i class="bi bi-cart"></i> {{ $item['total_qty'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-700">Rp{{ number_format($item['total_subtotal'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    @if ($idx === 0)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gradient-to-br from-amber-400 to-yellow-300 text-white text-xs font-bold shadow-md">1</span>
                                    @elseif ($idx === 1)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gradient-to-br from-gray-300 to-gray-200 text-white text-xs font-bold shadow-md">2</span>
                                    @elseif ($idx === 2)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gradient-to-br from-amber-700 to-amber-600 text-white text-xs font-bold shadow-md">3</span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 text-gray-500 text-xs font-bold">{{ $idx + 1 }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('turbo:load', function initCharts() {
    if (typeof Chart === 'undefined') return;
    var dc = document.getElementById('dailyChart');
    var mc = document.getElementById('monthlyChart');
    if (dc && !dc.chart) dc.chart = new Chart(dc, {
        type: 'bar',
        data: {
            labels: {!! json_encode($dailyLabels) !!},
            datasets: [{ label: '{{ __("Total Revenue") }} (Rp)', data: {!! json_encode($dailyData) !!}, backgroundColor: 'rgba(59, 130, 246, 0.5)', borderColor: 'rgb(59, 130, 246)', borderWidth: 1 }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp' + v.toLocaleString('id-ID') } } } }
    });
    if (mc && !mc.chart) mc.chart = new Chart(mc, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyLabels) !!},
            datasets: [{ label: '{{ __("Total Revenue") }} (Rp)', data: {!! json_encode($monthlyData) !!}, borderColor: 'rgb(16, 185, 129)', backgroundColor: 'rgba(16, 185, 129, 0.1)', fill: true, tension: 0.3 }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp' + v.toLocaleString('id-ID') } } } }
    });
});
</script>
@endpush
