@extends('layouts.app')

@section('title', __('Transaction History'))

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-200">{{ __('Transaction History') }}</h1>
        <div class="flex gap-2 w-full sm:w-auto">
            <button onclick="exportExcel()" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-white font-medium rounded-xl w-full sm:w-auto btn-scale" style="background:#059669">
                <i class="bi bi-file-earmark-excel"></i> {{ __('Export Excel') }}
            </button>
            <button onclick="exportPdf()" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-white font-medium rounded-xl w-full sm:w-auto btn-scale" style="background:#dc2626">
                <i class="bi bi-file-earmark-pdf"></i> {{ __('Export PDF') }}
            </button>
            <a href="{{ route('transactions.pos') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-white font-medium rounded-xl w-full sm:w-auto gradient-success btn-scale">
                <i class="bi bi-plus-lg"></i> {{ __('New Transaction') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-12 gap-2 mb-4">
        <div class="col-span-2 sm:col-span-4">
            <input type="text" id="searchInvoice" placeholder="{{ __('Search invoice') }}..." value="{{ request('search') }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
        </div>
        <div class="col-span-1 sm:col-span-3">
            <input type="date" id="dateFrom" value="{{ request('date_from') }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
        </div>
        <div class="col-span-1 sm:col-span-3">
            <input type="date" id="dateTo" value="{{ request('date_to') }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
        </div>
        <div class="col-span-2 sm:col-span-2 flex gap-2">
            <button id="searchBtn" class="flex-1 py-2.5 px-4 text-white font-medium rounded-xl gradient-primary btn-scale"><i class="bi bi-search"></i></button>
            <button id="resetBtn" class="flex-1 py-2.5 px-4 text-gray-600 dark:text-slate-300 font-medium rounded-xl bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 btn-scale"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4" id="summaryCards">
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <p class="text-xs text-gray-500 dark:text-slate-400 font-medium">{{ __('Total Revenue') }}</p>
            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400 mt-1" id="totalRevenueDisplay">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <p class="text-xs text-gray-500 dark:text-slate-400 font-medium">{{ __('Total Transactions') }}</p>
            <p class="text-lg font-bold text-gray-800 dark:text-slate-200 mt-1" id="totalCountDisplay">{{ $totalCount }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <p class="text-xs text-gray-500 dark:text-slate-400 font-medium">{{ __('Average') }}</p>
            <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400 mt-1" id="averageDisplay">Rp{{ number_format($average, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <p class="text-xs text-gray-500 dark:text-slate-400 font-medium">{{ __('Today Transactions') }}</p>
            <p class="text-lg font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $todayCount }}</p>
        </div>
    </div>

    <div id="transactionTableContainer">
        @include('transactions._table')
    </div>
@endsection

@push('scripts')
<script>
let searchTimer;

function fetchPage(url) {
    document.getElementById('transactionTableContainer').innerHTML = '<div class="text-center py-8 text-gray-400 dark:text-slate-500">{{ __("Loading") }}...</div>';
    window.history.replaceState({}, '', url);
    fetch(url, { headers: { 'Accept': 'application/json' } })
        .then(res => res.json())
        .then(data => {
            document.getElementById('transactionTableContainer').innerHTML = data.html;
            attachPaginationHandler();
            if (data.summary) {
                document.getElementById('totalRevenueDisplay').textContent = 'Rp' + data.summary.revenue;
                document.getElementById('totalCountDisplay').textContent = data.summary.count;
                document.getElementById('averageDisplay').textContent = 'Rp' + data.summary.average;
            }
        });
}

function doSearch(page) {
    const search = document.getElementById('searchInvoice').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;

    const url = new URL(window.location.href);
    url.searchParams.set('search', search);
    url.searchParams.set('date_from', dateFrom);
    url.searchParams.set('date_to', dateTo);
    url.searchParams.set('page', page || '1');
    fetchPage(url);
}

function attachPaginationHandler() {
    document.querySelectorAll('#transactionTableContainer nav a, #transactionTableContainer nav button').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            var href = this.tagName === 'A' ? this.href : (this.dataset.href || this.closest('a')?.href);
            if (href) fetchPage(href);
        });
    });
}

document.getElementById('searchInvoice').addEventListener('input', function() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(doSearch, 400);
});
document.getElementById('dateFrom').addEventListener('change', doSearch);
document.getElementById('dateTo').addEventListener('change', doSearch);
document.getElementById('searchBtn').addEventListener('click', doSearch);

document.getElementById('resetBtn').addEventListener('click', function() {
    document.getElementById('searchInvoice').value = '';
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
    doSearch();
});

document.getElementById('searchInvoice').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); doSearch(); }
});

function exportExcel() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const url = new URL('{{ route("transactions.export") }}');
    if (dateFrom) url.searchParams.set('date_from', dateFrom);
    if (dateTo) url.searchParams.set('date_to', dateTo);
    window.location.href = url;
}

function exportPdf() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const url = new URL('{{ route("transactions.export-pdf") }}');
    if (dateFrom) url.searchParams.set('date_from', dateFrom);
    if (dateTo) url.searchParams.set('date_to', dateTo);
    window.location.href = url;
}

attachPaginationHandler();
</script>
@endpush
