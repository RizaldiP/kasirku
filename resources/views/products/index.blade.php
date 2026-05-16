@extends('layouts.app')

@section('title', __('Products'))

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-200">{{ __('Products') }}</h1>
        <a href="{{ route('products.create') }}" class="btn-scale inline-flex items-center justify-center gap-2 px-5 py-2.5 gradient-primary text-white font-medium rounded-xl w-full sm:w-auto shadow-md shadow-blue-500/20">
            <i class="bi bi-plus-lg"></i> {{ __('Add Product') }}
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-12 gap-2 mb-6">
        <div class="col-span-2 sm:col-span-5">
            <input type="text" id="searchInput" placeholder="{{ __('Search product') }}..." value="{{ request('search') }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
        </div>
        <div class="col-span-1 sm:col-span-3">
            <select id="categoryFilter"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus appearance-none">
                <option value="">{{ __('All Categories') }}</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-span-1 sm:col-span-2">
            <button id="searchBtn" class="w-full py-2.5 px-4 gradient-primary text-white font-medium rounded-xl btn-scale shadow-md shadow-blue-500/20">
                <i class="bi bi-search"></i> {{ __('Search') }}
            </button>
        </div>
        <div class="col-span-2 sm:col-span-2">
            <a href="{{ route('products.index') }}" class="w-full py-2.5 px-4 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-medium rounded-xl btn-scale text-center block hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                <i class="bi bi-arrow-counterclockwise"></i> {{ __('Reset') }}
            </a>
        </div>
    </div>

    <div id="productTableContainer">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm table-card">
                    <thead>
                        <tr class="table-header-gradient text-gray-600 border-b border-gray-200 dark:border-slate-700">
                            <th class="px-4 py-3.5 text-left font-semibold">{{ __('Image') }}</th>
                            <th class="px-4 py-3.5 text-left font-semibold">{{ __('Name') }}</th>
                            <th class="px-4 py-3.5 text-left font-semibold hidden md:table-cell">Barcode</th>
                            <th class="px-4 py-3.5 text-left font-semibold hidden sm:table-cell">{{ __('Category') }}</th>
                            <th class="px-4 py-3.5 text-left font-semibold">{{ __('Sell Price') }}</th>
                            <th class="px-4 py-3.5 text-left font-semibold">{{ __('Stock') }}</th>
                            <th class="px-4 py-3.5 text-left font-semibold">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700" id="productTableBody">
                        @forelse ($products as $product)
                            <tr class="card-hover" data-id="{{ $product->id }}">
                                <td data-label="{{ __('Image') }}" class="px-4 py-3">
                                    @if ($product->image)
                                        <div class="w-12 h-12 bg-gray-100 dark:bg-slate-700 bg-cover bg-center bg-no-repeat rounded-lg ring-1 ring-gray-200 dark:ring-slate-600" style="background-image: url('{{ asset('storage/' . $product->image) }}')"></div>
                                    @else
                                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-slate-500"><i class="bi bi-image"></i></span>
                                    @endif
                                </td>
                                <td data-label="{{ __('Name') }}" class="px-4 py-3 font-semibold text-gray-900 dark:text-slate-200">{{ $product->name }}</td>
                                <td data-label="Barcode" class="px-4 py-3 font-mono text-gray-500 dark:text-slate-400 hidden md:table-cell">{{ $product->barcode ?? '-' }}</td>
                                <td data-label="{{ __('Category') }}" class="px-4 py-3 hidden sm:table-cell">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300">{{ $product->category ?? '-' }}</span>
                                </td>
                                <td data-label="{{ __('Sell Price') }}" class="px-4 py-3 font-medium text-gray-900 dark:text-slate-200">Rp{{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                <td data-label="{{ __('Stock') }}" class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->stock > 0 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' }}">
                                        <i class="bi {{ $product->stock > 0 ? 'bi-box' : 'bi-box-seam' }} me-1"></i>
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td data-label="{{ __('Action') }}" class="px-4 py-3">
                                    <div class="flex gap-1.5">
                                        <a href="{{ route('products.show', $product) }}" class="btn-scale inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 dark:border-slate-600 text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-700 dark:hover:text-slate-200 transition-colors" title="{{ __('Product Details') }}"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('products.edit', $product) }}" class="btn-scale inline-flex items-center justify-center w-9 h-9 rounded-lg border border-amber-200 dark:border-amber-700 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/30 transition-colors" title="{{ __('Edit') }}"><i class="bi bi-pencil"></i></a>
                                        <button onclick="deleteProduct({{ $product->id }}, this)" class="btn-scale inline-flex items-center justify-center w-9 h-9 rounded-lg border border-red-200 dark:border-red-700 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="{{ __('Delete') }}"><i class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow"><td colspan="7" class="px-4 py-12 text-center text-gray-400 dark:text-slate-500">
                                <i class="bi bi-inbox text-3xl block mb-2"></i>{{ __('No products.') }}
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div id="paginationContainer" class="mt-4 overflow-x-auto pagination-color">{{ $products->links() }}</div>
    </div>
@endsection

@push('scripts')
<script>
let searchTimer;

function fetchProductPage(url) {
    window.history.replaceState({}, '', url);
    document.getElementById('productTableContainer').innerHTML = '<div class="text-center py-8 text-gray-400"><i class="bi bi-arrow-repeat text-2xl animate-spin inline-block"></i><p class="mt-2">{{ __("Loading") }}...</p></div>';
    fetch(url)
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContainer = doc.getElementById('productTableContainer');
            if (newContainer) {
                document.getElementById('productTableContainer').innerHTML = newContainer.innerHTML;
            }
            attachDeleteHandlers();
            attachPaginationHandler();
        });
}

function doSearch(page) {
    const q = document.getElementById('searchInput').value;
    const cat = document.getElementById('categoryFilter').value;
    const url = new URL(window.location.href);
    url.searchParams.set('search', q);
    url.searchParams.set('category', cat);
    url.searchParams.set('page', page || '1');
    fetchProductPage(url);
}

function attachPaginationHandler() {
    document.querySelectorAll('#productTableContainer nav a, #productTableContainer nav button').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            var href = this.tagName === 'A' ? this.href : (this.dataset.href || this.closest('a')?.href);
            if (href) fetchProductPage(href);
        });
    });
}

document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(doSearch, 400);
});
document.getElementById('categoryFilter').addEventListener('change', doSearch);
document.getElementById('searchBtn').addEventListener('click', doSearch);
document.getElementById('searchInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); doSearch(); }
});

attachPaginationHandler();

function deleteProduct(id, btn) {
    if (!confirm('{{ __("Are you sure") }}?')) return;

    const row = btn.closest('tr');
    const token = document.querySelector('meta[name="csrf-token"]')?.content;

    fetch('/products/' + id, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
        body: new URLSearchParams({ _method: 'DELETE' }),
    })
    .then(res => {
        if (res.ok || res.redirected) {
            row.remove();
            const tbody = document.getElementById('productTableBody');
            if (tbody && tbody.querySelectorAll('tr').length === 0) {
                tbody.innerHTML = '<tr id="emptyRow"><td colspan="7" class="px-4 py-12 text-center text-gray-400"><i class="bi bi-inbox text-3xl block mb-2"></i>{{ __("No products.") }}</td></tr>';
            }
            const alert = document.createElement('div');
            alert.className = 'flex items-center justify-between p-4 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 shadow-sm';
            alert.innerHTML = '<span><i class="bi bi-check-circle-fill text-emerald-500 me-2"></i>{{ __("Product deleted") }}.</span><button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-xl leading-none">&times;</button>';
            document.querySelector('main').insertBefore(alert, document.querySelector('main').firstChild);
            setTimeout(() => alert.remove(), 3000);
        } else {
            res.json().then(data => alert(data.message || '{{ __("Failed to delete product") }}'));
        }
    })
    .catch(() => alert('{{ __("An error occurred") }}'));
}

function attachDeleteHandlers() {
    document.querySelectorAll('[onclick^="deleteProduct"]').forEach(el => {
        const match = el.getAttribute('onclick').match(/deleteProduct\((\d+),\s*this\)/);
        if (match) {
            el.removeAttribute('onclick');
            el.addEventListener('click', function() { deleteProduct(parseInt(match[1]), this); });
        }
    });
}
</script>
@endpush
