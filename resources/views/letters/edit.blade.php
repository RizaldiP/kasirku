@extends('layouts.app')

@section('title', __('Edit Letter'))

@section('content')
<div class="max-w-3xl mx-auto">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-200">{{ __('Edit Letter') }}</h1>
    <a href="{{ route('letters.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 font-medium rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600 btn-scale">
      <i class="bi bi-arrow-left"></i> {{ __('Back') }}
    </a>
  </div>

  @if ($errors->any())
    <div class="alert p-4 mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 shadow-sm">
      <ul class="mb-0 list-disc list-inside text-sm">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('letters.update', $letter) }}" method="POST" class="space-y-4">
    @csrf @method('PUT')

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 card-hover p-5 space-y-4">
      <h2 class="font-bold text-gray-900 dark:text-slate-200 text-lg"><i class="bi bi-info-circle text-indigo-600 me-2"></i>{{ __('Letter Info') }}</h2>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Letter Number') }} <span class="text-red-500">*</span></label>
          <input type="text" name="letter_number" value="{{ old('letter_number', $letter->letter_number) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Date') }} <span class="text-red-500">*</span></label>
          <input type="date" name="date" value="{{ old('date', $letter->date) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Attachment') }}</label>
          <input type="text" name="attachment_count" value="{{ old('attachment_count', $letter->attachment_count) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Subject') }} <span class="text-red-500">*</span></label>
        <input type="text" name="subject" value="{{ old('subject', $letter->subject) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 card-hover p-5 space-y-4">
      <h2 class="font-bold text-gray-900 dark:text-slate-200 text-lg"><i class="bi bi-person text-purple-600 me-2"></i>{{ __('Recipient') }}</h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Recipient Name') }} <span class="text-red-500">*</span></label>
          <input type="text" name="recipient_name" value="{{ old('recipient_name', $letter->recipient_name) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Recipient Place') }}</label>
          <input type="text" name="recipient_place" value="{{ old('recipient_place', $letter->recipient_place) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
        </div>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 card-hover p-5 space-y-4">
      <h2 class="font-bold text-gray-900 dark:text-slate-200 text-lg"><i class="bi bi-person-badge text-emerald-600 me-2"></i>{{ __('Sender') }}</h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Sender Name') }} <span class="text-red-500">*</span></label>
          <input type="text" name="sender_name" value="{{ old('sender_name', $letter->sender_name) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Sender Position') }} <span class="text-red-500">*</span></label>
          <input type="text" name="sender_position" value="{{ old('sender_position', $letter->sender_position) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Sender Address') }}</label>
        <textarea name="sender_address" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">{{ old('sender_address', $letter->sender_address) }}</textarea>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 card-hover p-5 space-y-4">
      <h2 class="font-bold text-gray-900 dark:text-slate-200 text-lg"><i class="bi bi-file-text text-amber-600 me-2"></i>{{ __('Body') }}</h2>

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Letter Body') }} <span class="text-red-500">*</span></label>
        <textarea name="body" rows="10" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus text-sm leading-relaxed">{{ old('body', $letter->body) }}</textarea>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Place') }}</label>
          <input type="text" name="place" value="{{ old('place', $letter->place) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
        </div>
      </div>
    </div>

    <button type="submit" class="w-full py-2.5 px-4 gradient-primary text-white font-medium rounded-xl btn-scale">
      <i class="bi bi-save me-1"></i> {{ __('Update Letter') }}
    </button>
  </form>
</div>
@endsection