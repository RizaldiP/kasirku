@extends('layouts.app')

@section('title', __('Add Product'))

@section('content')
    <div class="flex items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-200">{{ __('Add Product') }}</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-6">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-1 block">{{ __('Product Name') }}</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-1 block">Barcode</label>
                        <input type="text" name="barcode" value="{{ old('barcode') }}" placeholder="{{ __('Scan or type barcode') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-1 block">{{ __('Category') }}</label>
                        <input type="text" name="category" value="{{ old('category') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-1 block">{{ __('Image') }}</label>
                        <input type="file" name="image" accept="image/jpeg,image/png"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-1 block">{{ __('Sell Price') }}</label>
                        <input type="number" name="selling_price" value="{{ old('selling_price') }}" step="0.01" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-1 block">{{ __('Buy Price') }}</label>
                        <input type="number" name="purchase_price" value="{{ old('purchase_price') }}" step="0.01" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-1 block">{{ __('Stock') }}</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 mt-6">
                    <button type="submit" class="btn-scale flex-1 py-2.5 px-4 gradient-primary text-white font-medium rounded-xl shadow-md shadow-blue-500/20 inline-flex items-center justify-center gap-2">
                        <i class="bi bi-check-lg"></i> {{ __('Save') }}
                    </button>
                    <a href="{{ route('products.index') }}" class="btn-scale flex-1 py-2.5 px-4 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-medium rounded-xl text-center inline-flex items-center justify-center gap-2 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                        <i class="bi bi-x-lg"></i> {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
