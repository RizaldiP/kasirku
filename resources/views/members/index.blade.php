@extends('layouts.app')

@section('title', __('Member Data'))

@section('content')
<div class="mb-6 p-6 rounded-2xl" style="background: linear-gradient(135deg, #7c3aed, #db2777);">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('Member Data') }}</h1>
            <p class="text-purple-200 mt-1 text-sm">{{ __('Manage member data and points.') }}</p>
        </div>
        <a href="{{ route('members.create') }}" class="btn-scale inline-flex items-center gap-1.5 px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-xl transition-all text-sm">
            <i class="bi bi-person-plus"></i> {{ __('Add Member') }}
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                <i class="bi bi-people text-lg text-white"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 dark:text-slate-200">{{ __('Member Data') }}</h3>
                <p class="text-gray-500 dark:text-slate-400 text-xs">{{ $members->total() }} {{ __('Members') }} {{ __('registered') }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <input type="text" id="searchMember" placeholder="{{ __('Search member') }}..." class="w-48 px-3 py-1.5 border border-gray-300 rounded-lg text-sm input-focus">
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm table-card">
            <thead class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-slate-700 dark:to-slate-800 text-gray-600 dark:text-slate-300">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">{{ __('Name') }}</th>
                    <th class="px-4 py-3 text-left font-semibold">{{ __('Phone') }}</th>
                    <th class="px-4 py-3 text-left font-semibold">{{ __('Email') }}</th>
                    <th class="px-4 py-3 text-center font-semibold">{{ __('Points') }}</th>
                    <th class="px-4 py-3 text-right font-semibold">{{ __('Total Spending') }}</th>
                    <th class="px-4 py-3 text-center font-semibold">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($members as $member)
                <tr class="hover:bg-purple-50/50 transition-colors">
                    <td class="px-4 py-3" data-label="{{ __('Name') }}">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <a href="{{ route('members.show', $member) }}" class="font-medium text-gray-800 dark:text-slate-200 hover:text-purple-600">{{ $member->name }}</a>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600 dark:text-slate-400" data-label="{{ __('Phone') }}">{{ $member->phone }}</td>
                    <td class="px-4 py-3 text-gray-500 dark:text-slate-400" data-label="{{ __('Email') }}">{{ $member->email ?? '-' }}</td>
                    <td class="px-4 py-3 text-center" data-label="{{ __('Points') }}">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold {{ $member->points > 0 ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300' : 'bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-slate-400' }}">
                            <i class="bi bi-star-fill"></i> {{ number_format($member->points) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right font-semibold text-emerald-600 dark:text-emerald-400" data-label="{{ __('Total Spending') }}">Rp{{ number_format($member->total_spent, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-center" data-label="{{ __('Action') }}">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('members.show', $member) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition-all" title="{{ __('Detail') }}">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('members.edit', $member) }}" class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition-all" title="{{ __('Edit') }}">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button onclick="deleteMember({{ $member->id }}, '{{ addslashes($member->name) }}')" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-all" title="{{ __('Delete') }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400 dark:text-slate-500">
                        <i class="bi bi-people text-4xl block mb-2"></i>
                        <p>{{ __('No members registered.') }}</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-3 border-t border-gray-100">
        {{ $members->links() }}
    </div>
</div>

<form id="deleteMemberForm" method="POST" class="hidden">
    @csrf @method('DELETE')
</form>

<script>
function deleteMember(id, name) {
    if (!confirm('{{ __("Are you sure") }} ' + name + '?')) return;
    var form = document.getElementById('deleteMemberForm');
    form.action = '{{ url('members') }}/' + id;
    form.submit();
}
</script>
@endsection
