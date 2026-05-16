@extends('layouts.app')
@section('title', __('User Data'))
@section('content')
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-200">{{ __('User Data') }}</h1>
    <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 gradient-primary text-white font-medium rounded-xl btn-scale w-full sm:w-auto shadow-md">
      <i class="bi bi-plus-lg"></i> {{ __('Add User') }}
    </a>
  </div>

  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover">
    <div class="overflow-x-auto">
      <table class="w-full text-sm table-card">
        <thead>
          <tr class="table-header-gradient text-gray-600 dark:text-slate-300">
            <th class="px-4 py-3 text-left font-medium">{{ __('Name') }}</th>
            <th class="px-4 py-3 text-left font-medium hidden sm:table-cell">{{ __('Email') }}</th>
            <th class="px-4 py-3 text-left font-medium">{{ __('Role') }}</th>
            <th class="px-4 py-3 text-left font-medium hidden md:table-cell">{{ __('Created') }}</th>
            <th class="px-4 py-3 text-left font-medium">{{ __('Action') }}</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-slate-700" id="userTableBody">
          @forelse ($users as $user)
            <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-purple-50/20 dark:hover:from-slate-800 dark:hover:to-slate-700 transition-all" data-id="{{ $user->id }}">
              <td data-label="{{ __('Name') }}" class="px-4 py-3 font-semibold dark:text-slate-200">{{ $user->name }}</td>
              <td data-label="{{ __('Email') }}" class="px-4 py-3 hidden sm:table-cell text-gray-600 dark:text-slate-400">{{ $user->email }}</td>
              <td data-label="{{ __('Role') }}" class="px-4 py-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' }}">
                  {{ ucfirst($user->role) }}
                </span>
              </td>
              <td data-label="{{ __('Created') }}" class="px-4 py-3 hidden md:table-cell text-gray-500 dark:text-slate-400 text-sm">{{ $user->created_at->format('d M Y') }}</td>
              <td data-label="{{ __('Action') }}" class="px-4 py-3">
                <div class="flex gap-1">
                  <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-amber-200 dark:border-amber-700 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/30 hover:border-amber-300 transition-all" title="{{ __('Edit') }}"><i class="bi bi-pencil"></i></a>
                  @if ($user->id !== auth()->id())
                    <button onclick="deleteUser({{ $user->id }}, this)" class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-red-200 dark:border-red-700 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 hover:border-red-300 transition-all" title="{{ __('Delete') }}"><i class="bi bi-trash"></i></button>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400 dark:text-slate-500">{{ __('No users.') }}</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-4 pagination-color">{{ $users->links() }}</div>
@endsection

@push('scripts')
<script>
function deleteUser(id, btn) {
    if (!confirm('{{ __("Are you sure") }}?')) return;
    const row = btn.closest('tr');
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    fetch('/users/' + id, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
        body: new URLSearchParams({ _method: 'DELETE' }),
    })
    .then(res => {
        if (res.ok || res.redirected) {
            row.remove();
            const tbody = document.getElementById('userTableBody');
            if (tbody && tbody.querySelectorAll('tr').length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">{{ __("No users.") }}</td></tr>';
            }
            const alert = document.createElement('div');
            alert.className = 'flex items-center justify-between p-4 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 shadow-sm';
            alert.innerHTML = '<span><i class="bi bi-check-circle-fill text-emerald-500 me-2"></i>{{ __("User deleted") }}.</span><button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-xl leading-none">&times;</button>';
            document.querySelector('main').insertBefore(alert, document.querySelector('main').firstChild);
            setTimeout(() => alert.remove(), 3000);
        } else {
            res.json().then(data => alert(data.message || '{{ __("Failed to delete user") }}'));
        }
    })
    .catch(() => alert('{{ __("An error occurred") }}'));
}
</script>
@endpush
