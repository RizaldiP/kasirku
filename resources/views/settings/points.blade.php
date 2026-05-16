@extends('layouts.app')

@section('title', __('Points Settings'))

@section('content')
<div class="mb-6 p-6 rounded-2xl" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
    <div class="flex items-center gap-3">
        <a href="{{ route('dashboard.admin') }}" class="text-white/80 hover:text-white">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('Points Settings') }}</h1>
            <p class="text-yellow-100 mt-1 text-sm">{{ __('Configure how member points work.') }}</p>
        </div>
    </div>
</div>

<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <form method="POST" action="{{ route('settings.points.update') }}">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Points per Purchase') }}</label>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 dark:text-slate-400">1 {{ __('Point') }} {{ __('per') }} Rp</span>
                    <input type="number" name="earn_per_amount" value="{{ old('earn_per_amount', $earnPerAmount) }}" min="100" class="w-28 px-3 py-2 border border-gray-300 rounded-xl input-focus text-center text-sm">
                </div>
                <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">{{ __('Example') }}: Rp10.000 = 1 {{ __('Point') }}</p>
                @error('earn_per_amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">{{ __('Redeem Rate') }}</label>
                <div class="flex items-center gap-2">
                    <input type="number" name="redeem_per_discount" value="{{ old('redeem_per_discount', $redeemPerDiscount) }}" min="1" class="w-20 px-3 py-2 border border-gray-300 rounded-xl input-focus text-center text-sm">
                    <span class="text-sm text-gray-500 dark:text-slate-400">{{ __('points discount') }} = Rp</span>
                    <input type="number" name="discount_per_unit" value="{{ old('discount_per_unit', $discountPerUnit) }}" min="100" class="w-28 px-3 py-2 border border-gray-300 rounded-xl input-focus text-center text-sm">
                    <span class="text-sm text-gray-500 dark:text-slate-400">{{ __('Discount per Redeem') }}</span>
                </div>
                <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">{{ __('Example') }}: 100 {{ __('points discount') }} = Rp{{ number_format($discountPerUnit, 0, ',', '.') }} {{ __('Discount per Redeem') }}</p>
                @error('redeem_per_discount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                @error('discount_per_unit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <hr class="border-gray-100 dark:border-slate-700 my-5">

            <div class="bg-amber-50 dark:bg-amber-900/30 rounded-xl p-4 mb-5 text-sm text-amber-800 dark:text-amber-300">
                <div class="flex gap-2">
                    <i class="bi bi-info-circle-fill text-amber-500 mt-0.5"></i>
                    <div>
                        <p class="font-medium">{{ __('Simulation') }}</p>
                        <p class="text-amber-700 dark:text-amber-400 mt-1">
                            {{ __('Shopping') }} Rp50.000 → <strong>{{ floor(50000 / $earnPerAmount) }} {{ __('points') }}</strong><br>
                            {{ __('Redeem') }} 100 {{ __('points') }} → {{ __('Discount per Redeem') }} <strong>Rp{{ number_format($discountPerUnit, 0, ',', '.') }}</strong>
                        </p>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl text-white font-bold btn-scale shadow-md" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
                <i class="bi bi-check-lg me-1"></i> {{ __('Save Settings') }}
            </button>
        </form>
    </div>
</div>
@endsection
