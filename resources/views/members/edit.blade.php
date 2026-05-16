@extends('layouts.app')

@section('title', __('Edit Member'))

@section('content')
<div class="mb-6 p-6 rounded-2xl" style="background: linear-gradient(135deg, #7c3aed, #db2777);">
    <div class="flex items-center gap-3">
        <a href="{{ route('members.index') }}" class="text-white/80 hover:text-white">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('Edit Member') }}</h1>
            <p class="text-purple-200 mt-1 text-sm">{{ $member->name }}</p>
        </div>
    </div>
</div>

<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <form method="POST" action="{{ route('members.update', $member) }}">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Name') }}</label>
                <input type="text" name="name" value="{{ old('name', $member->name) }}" required
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus" placeholder="{{ __('Name') }} {{ __('Member') }}">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Phone') }}</label>
                <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" required
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus" placeholder="08123456789">
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Email') }} <span class="text-gray-400 dark:text-slate-500">({{ __('optional') }})</span></label>
                <input type="email" name="email" value="{{ old('email', $member->email) }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus" placeholder="email@example.com">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-2">
                <a href="{{ route('members.index') }}" class="flex-1 py-3 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 font-medium rounded-xl text-center hover:bg-gray-200 dark:hover:bg-slate-600 transition-all">{{ __('Cancel') }}</a>
                <button type="submit" class="flex-1 py-3 gradient-purple text-white font-bold rounded-xl btn-scale shadow-md">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
