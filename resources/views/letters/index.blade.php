@extends('layouts.app')

@section('title', __('Letters'))

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-200">{{ __('Letters') }}</h1>
    <a href="{{ route('letters.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 gradient-primary text-white font-medium rounded-xl w-full sm:w-auto btn-scale shadow-md shadow-blue-500/20">
        <i class="bi bi-plus-lg"></i> {{ __('Create Letter') }}
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm table-card">
            <thead>
                <tr class="table-header-gradient text-gray-600 border-b border-gray-200 dark:border-slate-700">
                    <th class="px-4 py-3.5 text-left font-semibold">{{ __('Letter Number') }}</th>
                    <th class="px-4 py-3.5 text-left font-semibold hidden sm:table-cell">{{ __('Subject') }}</th>
                    <th class="px-4 py-3.5 text-left font-semibold hidden md:table-cell">{{ __('Recipient') }}</th>
                    <th class="px-4 py-3.5 text-left font-semibold hidden md:table-cell">{{ __('Date') }}</th>
                    <th class="px-4 py-3.5 text-left font-semibold">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($letters as $letter)
                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-all dark:border-slate-700 dark:hover:bg-slate-800/50">
                    <td class="px-4 py-3.5 font-medium text-gray-900 dark:text-slate-200" data-label="{{ __('Letter Number') }}">{{ $letter->letter_number }}</td>
                    <td class="px-4 py-3.5 text-gray-700 dark:text-slate-300 hidden sm:table-cell" data-label="{{ __('Subject') }}">{{ $letter->subject }}</td>
                    <td class="px-4 py-3.5 text-gray-600 dark:text-slate-400 hidden md:table-cell" data-label="{{ __('Recipient') }}">{{ $letter->recipient_name }}</td>
                    <td class="px-4 py-3.5 text-gray-600 dark:text-slate-400 hidden md:table-cell" data-label="{{ __('Date') }}">{{ \Carbon\Carbon::parse($letter->date)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3.5" data-label="{{ __('Actions') }}">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('letters.show', $letter) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-all dark:text-indigo-400 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('letters.edit', $letter) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 transition-all dark:text-amber-400 dark:bg-amber-900/30 dark:hover:bg-amber-900/50">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('letters.export-word', $letter) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-all dark:text-emerald-400 dark:bg-emerald-900/30 dark:hover:bg-emerald-900/50">
                                <i class="bi bi-file-word"></i>
                            </a>
                            <form action="{{ route('letters.destroy', $letter) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure') }}?')">
                                @csrf @method('DELETE')
                                <button class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-all dark:text-red-400 dark:bg-red-900/30 dark:hover:bg-red-900/50">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-gray-400 dark:text-slate-500">
                        <i class="bi bi-file-text text-3xl block mb-2"></i>
                        {{ __('No letters yet') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 pagination-color">
    {{ $letters->links() }}
</div>
@endsection