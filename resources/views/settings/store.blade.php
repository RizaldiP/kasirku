@extends('layouts.app')

@section('title', __('Store Settings'))

@section('content')
<div class="max-w-2xl mx-auto">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-200">{{ __('Store Settings') }}</h1>
    <a href="{{ route('dashboard.admin') }}" class="inline-flex items-center gap-2 px-4 py-2.5 font-medium rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600 btn-scale">
      <i class="bi bi-arrow-left"></i> {{ __('Back') }}
    </a>
  </div>

  @if (session('success'))
    <div class="alert flex items-center gap-2 p-4 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 shadow-sm card-hover">
      <i class="bi bi-check-circle-fill text-emerald-500"></i>{{ session('success') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="alert p-4 mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 shadow-sm">
      <ul class="mb-0 list-disc list-inside text-sm">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('settings.store.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 card-hover">
      <div class="p-5">
        <h2 class="font-bold text-gray-900 dark:text-slate-200 mb-1"><i class="bi bi-shop text-indigo-600 me-2"></i>{{ __('Store Name') }}</h2>
        <p class="text-sm text-gray-500 dark:text-slate-400 mb-4">{{ __('The name will appear in the navbar, receipt, and POS page.') }}</p>
        <input type="text" name="store_name" value="{{ old('store_name', $storeName) }}" required maxlength="255" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus text-lg font-bold">
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 card-hover">
      <div class="p-5">
        <h2 class="font-bold text-gray-900 dark:text-slate-200 mb-1"><i class="bi bi-image text-purple-600 me-2"></i>{{ __('Store Logo') }}</h2>
        <p class="text-sm text-gray-500 dark:text-slate-400 mb-4">{{ __('Upload a logo image (PNG/JPG, max 2MB). Recommended size: 64x64 px.') }}</p>

        <div class="flex flex-col items-center gap-4">
          <div class="w-24 h-24 rounded-2xl overflow-hidden border-2 border-gray-200 dark:border-slate-600 flex items-center justify-center bg-gradient-to-br from-gray-50 to-indigo-50 dark:from-slate-700 dark:to-slate-800">
            @if ($logoExists)
              <img src="{{ asset('storage/logo.png') . '?v=' . time() }}" alt="{{ __('Store Logo') }}" class="w-full h-full object-cover">
            @else
              <i class="bi bi-shop text-4xl text-gray-300 dark:text-slate-500"></i>
            @endif
          </div>
          <label class="flex items-center gap-3 px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 transition-all w-full max-w-sm">
            <i class="bi bi-cloud-upload text-2xl text-gray-400"></i>
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-700 dark:text-slate-300">{{ $logoExists ? __('Change Logo') : __('Upload Logo') }}</p>
              <p class="text-xs text-gray-400 dark:text-slate-500">PNG {{ __('or') }} JPG, {{ __('max') }} 2MB</p>
            </div>
            <input type="file" name="logo" accept="image/png,image/jpeg" class="hidden">
          </label>
        </div>
      </div>
    </div>

    <button type="submit" class="w-full py-2.5 px-4 gradient-primary text-white font-medium rounded-xl btn-scale">
      <i class="bi bi-save me-1"></i> {{ __('Save Settings') }}
    </button>
  </form>
</div>
@endsection