@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-200">{{ $product->name }}</h1>
        <div class="flex gap-2 w-full sm:w-auto">
            <a href="{{ route('products.edit', $product) }}" class="btn-scale flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 gradient-warning text-white font-medium rounded-xl shadow-md shadow-orange-500/20">
                <i class="bi bi-pencil"></i> {{ __('Edit') }}
            </a>
            <a href="{{ route('products.index') }}" class="btn-scale flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-medium rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                <i class="bi bi-arrow-left"></i> {{ __('Back to List') }}
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    @if ($product->image)
                        <div class="w-full h-48 bg-gray-100 dark:bg-slate-700 bg-cover bg-center bg-no-repeat rounded-xl ring-1 ring-gray-200 dark:ring-slate-600" style="background-image: url('{{ asset('storage/' . $product->image) }}')"></div>
                    @else
                        <div class="bg-gray-100 dark:bg-slate-700 rounded-xl flex items-center justify-center text-gray-400 dark:text-slate-500 h-48">
                            <div class="text-center">
                                <i class="bi bi-image text-4xl block mb-2"></i>
                                <span class="text-sm">{{ __('No image') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="md:col-span-2">
                    <table class="w-full">
                        <tr class="border-b border-gray-100 dark:border-slate-700">
                            <td class="font-semibold text-gray-600 dark:text-slate-300 w-32 py-3">{{ __('Name') }}</td>
                            <td class="py-3 font-medium dark:text-slate-200">{{ $product->name }}</td>
                        </tr>
                        <tr class="border-b border-gray-100 dark:border-slate-700">
                            <td class="font-semibold text-gray-600 dark:text-slate-300 py-3">Barcode</td>
                            <td class="py-3 font-mono text-gray-500 dark:text-slate-400">{{ $product->barcode ?? '-' }}</td>
                        </tr>
                        <tr class="border-b border-gray-100 dark:border-slate-700">
                            <td class="font-semibold text-gray-600 dark:text-slate-300 py-3">{{ __('Category') }}</td>
                            <td class="py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300">{{ $product->category ?? '-' }}</span>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-100 dark:border-slate-700">
                            <td class="font-semibold text-gray-600 dark:text-slate-300 py-3">{{ __('Sell Price') }}</td>
                            <td class="py-3 text-emerald-600 dark:text-emerald-400 font-bold text-lg">Rp{{ number_format($product->selling_price, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-b border-gray-100 dark:border-slate-700">
                            <td class="font-semibold text-gray-600 dark:text-slate-300 py-3">{{ __('Buy Price') }}</td>
                            <td class="py-3 dark:text-slate-200">Rp{{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-b border-gray-100 dark:border-slate-700">
                            <td class="font-semibold text-gray-600 dark:text-slate-300 py-3">{{ __('Stock') }}</td>
                            <td class="py-3">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium {{ $product->stock > 0 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' }}">
                                    <i class="bi {{ $product->stock > 0 ? 'bi-box' : 'bi-box-seam' }}"></i>
                                    {{ $product->stock }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-semibold text-gray-600 dark:text-slate-300 py-3">{{ __('Created') }}</td>
                            <td class="py-3 text-gray-500 dark:text-slate-400">{{ $product->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
