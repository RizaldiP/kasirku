@extends('layouts.app')

@section('title', __('Member Details'))

@section('content')
<div class="mb-6 p-6 rounded-2xl" style="background: linear-gradient(135deg, #7c3aed, #db2777);">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('members.index') }}" class="text-white/80 hover:text-white">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-white text-xl font-bold">
                {{ strtoupper(substr($member->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $member->name }}</h1>
                <p class="text-purple-200 text-sm">{{ $member->phone }}</p>
            </div>
        </div>
        <a href="{{ route('members.edit', $member) }}" class="btn-scale inline-flex items-center gap-1.5 px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-xl transition-all text-sm">
            <i class="bi bi-pencil"></i> {{ __('Edit') }}
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 card-hover">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                <i class="bi bi-star-fill text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-slate-400 font-medium">{{ __('Total Points') }}</p>
                <p class="text-xl font-bold text-gray-800 dark:text-slate-200">{{ number_format($member->points) }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 card-hover">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <i class="bi bi-cash-stack text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-slate-400 font-medium">{{ __('Total Spending') }}</p>
                <p class="text-xl font-bold text-gray-800 dark:text-slate-200">Rp{{ number_format($member->total_spent, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 card-hover">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <i class="bi bi-receipt text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-slate-400 font-medium">{{ __('Total Transactions') }}</p>
                <p class="text-xl font-bold text-gray-800 dark:text-slate-200">{{ $transactions->count() }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Points Log -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-400 rounded-lg flex items-center justify-center">
                    <i class="bi bi-activity text-lg text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-slate-200">{{ __('Points Log') }}</h3>
                    <p class="text-gray-500 dark:text-slate-400 text-xs">50 {{ __('transactions') }}</p>
                </div>
            </div>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-slate-700 max-h-80 overflow-y-auto">
            @forelse ($pointsLog as $log)
            <div class="px-4 py-3 flex items-center justify-between hover:bg-gray-50/50 dark:hover:bg-slate-800/50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full {{ $log->type === 'earn' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-400' : 'bg-red-100 text-red-600 dark:bg-red-900/50 dark:text-red-400' }} flex items-center justify-center">
                        <i class="bi {{ $log->type === 'earn' ? 'bi-plus' : 'bi-dash' }} text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium {{ $log->type === 'earn' ? 'text-emerald-700 dark:text-emerald-400' : 'text-red-700 dark:text-red-400' }}">
                            {{ $log->type === 'earn' ? __('Earned') : __('Redeemed') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-slate-400">
                            {{ $log->transaction ? '#' . $log->transaction->invoice_number : '' }}
                            &middot; {{ $log->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <span class="text-sm font-bold {{ $log->points > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                    {{ $log->points > 0 ? '+' : '' }}{{ number_format($log->points) }}
                </span>
            </div>
            @empty
            <div class="px-4 py-8 text-center text-gray-400 dark:text-slate-500">
                <i class="bi bi-inbox text-3xl block mb-2"></i>
                <p class="text-sm">{{ __('No points log') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Transaction History -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-lg flex items-center justify-center">
                    <i class="bi bi-receipt text-lg text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-slate-200">{{ __('Transaction History Member') }}</h3>
                    <p class="text-gray-500 dark:text-slate-400 text-xs">20 {{ __('transactions') }}</p>
                </div>
            </div>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-slate-700 max-h-80 overflow-y-auto">
            @forelse ($transactions as $t)
            <a href="{{ route('transactions.show', $t) }}" class="px-4 py-3 flex items-center justify-between hover:bg-blue-50/50 dark:hover:bg-blue-900/20 transition-all block">
                <div>
                    <p class="text-sm font-mono text-blue-700 dark:text-blue-400">#{{ $t->invoice_number }}</p>
                    <p class="text-xs text-gray-500 dark:text-slate-400">{{ $t->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">Rp{{ number_format($t->total_price, 0, ',', '.') }}</p>
                    @if ($t->points_earned > 0)
                    <p class="text-xs text-amber-600 dark:text-amber-400">+{{ $t->points_earned }} {{ __('Points') }}</p>
                    @endif
                </div>
            </a>
            @empty
            <div class="px-4 py-8 text-center text-gray-400 dark:text-slate-500">
                <i class="bi bi-inbox text-3xl block mb-2"></i>
                <p class="text-sm">{{ __('No transactions member') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
