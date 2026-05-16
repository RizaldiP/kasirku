@extends('layouts.app')
@section('title', __('Edit User'))
@section('content')
  <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-200 mb-4">{{ __('Edit User') }}</h1>
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 card-hover">
    <div class="p-6">
      <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Name') }}</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Email') }}</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Password') }} <span class="text-gray-400 dark:text-slate-500 font-normal">({{ __('leave blank if not changed') }})</span></label>
            <input type="password" name="password" min="6" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Role') }}</label>
            <select name="role" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus appearance-none bg-white dark:bg-slate-800">
              <option value="kasir" {{ old('role', $user->role) === 'kasir' ? 'selected' : '' }}>{{ __('Cashier') }}</option>
              <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
            </select>
          </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 mt-6">
          <button type="submit" class="flex-1 py-2.5 px-4 gradient-primary text-white font-medium rounded-xl btn-scale shadow-md"><i class="bi bi-check-lg me-1"></i> {{ __('Save') }}</button>
          <a href="{{ route('users.index') }}" class="flex-1 py-2.5 px-4 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-medium rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-all text-center btn-scale"><i class="bi bi-x-lg me-1"></i> {{ __('Cancel') }}</a>
        </div>
      </form>
    </div>
  </div>
@endsection
