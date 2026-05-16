<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="{{ session('_dark_mode', false) ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $storeName)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="dark-mode" content="{{ session('_dark_mode', false) ? '1' : '0' }}">
    @vite('resources/css/app.css')
    <style>
        body {
            background: linear-gradient(135deg, #f0f4ff 0%, #fdf2f8 50%, #f0fdf4 100%);
            background-attachment: fixed;
        }
        .dark body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
        }
        .nav-gradient { background: linear-gradient(135deg, #2563eb, #4f46e5); }
        .nav-link-active { background: rgba(255,255,255,0.12); color: #fff !important; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 12px 24px -8px rgba(0,0,0,0.1); }
        .btn-scale { transition: all 0.2s ease; }
        .btn-scale:hover { transform: scale(1.02); }
        .btn-scale:active { transform: scale(0.98); }
        .gradient-primary { background: linear-gradient(135deg, #2563eb, #4f46e5); }
        .gradient-success { background: linear-gradient(135deg, #059669, #0d9488); }
        .gradient-warning { background: linear-gradient(135deg, #d97706, #ea580c); }
        .gradient-danger { background: linear-gradient(135deg, #dc2626, #e11d48); }
        .gradient-info { background: linear-gradient(135deg, #0891b2, #6366f1); }
        .gradient-purple { background: linear-gradient(135deg, #7c3aed, #db2777); }
        .table-header-gradient { background: linear-gradient(135deg, #f8faff, #f0f4ff); }
        .input-focus { transition: all 0.2s ease; }
        .input-focus:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .pagination-color nav span[aria-current="page"] span,
        .pagination-color nav .active { background: linear-gradient(135deg, #2563eb, #4f46e5) !important; border-color: transparent !important; color: #fff !important; }

        .dark .table-header-gradient { background: linear-gradient(135deg, #1e293b, #334155); }
        .dark .pagination-color nav span[aria-current="page"] span,
        .dark .pagination-color nav .active { background: linear-gradient(135deg, #3b82f6, #6366f1) !important; }
        @media (max-width: 640px) {
            .table-card tbody, .table-card tbody tr { display: block; }
            .table-card tbody tr {
                border: 1px solid #e5e7eb; border-radius: 0.75rem;
                margin-bottom: 0.5rem; padding: 0.75rem 1rem; background: #fff;
                box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            }
            .dark .table-card tbody tr { background: #1e293b; border-color: #334155; }
            .table-card tbody tr td {
                display: flex; justify-content: space-between; align-items: center;
                padding: 0.3rem 0; border: none; gap: 0.5rem;
            }
            .table-card tbody tr td::before {
                content: attr(data-label); font-weight: 600;
                font-size: 0.75rem; color: #6b7280; white-space: nowrap;
            }
            .dark .table-card tbody tr td::before { color: #94a3b8; }
            .table-card thead { display: none; }
        }

        .dark, .dark * { --tw-bg-opacity: 1; }

        .dark .bg-white { background-color: #1e293b !important; }
        .dark .bg-white\/80 { background-color: #1e293b !important; }
        .dark .bg-gray-50 { background-color: #0f172a !important; }
        .dark .bg-gray-100 { background-color: #1e293b !important; }

        .dark .text-gray-900 { color: #f1f5f9 !important; }
        .dark .text-gray-800 { color: #e2e8f0 !important; }
        .dark .text-gray-700 { color: #cbd5e1 !important; }
        .dark .text-gray-600 { color: #94a3b8 !important; }
        .dark .text-gray-500 { color: #64748b !important; }
        .dark .text-gray-400 { color: #475569 !important; }

        .dark .border-gray-100 { border-color: #334155 !important; }
        .dark .border-gray-200 { border-color: #334155 !important; }
        .dark .border-gray-300 { border-color: #475569 !important; }

        .dark .shadow-sm { box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
        .dark .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.4); }

        .dark .bg-indigo-50 { background-color: #1e1b4b !important; }
        .dark .bg-indigo-100 { background-color: #3730a3 !important; }
        .dark .text-indigo-600 { color: #a5b4fc !important; }
        .dark .text-indigo-700 { color: #a5b4fc !important; }
        .dark .text-indigo-800 { color: #c7d2fe !important; }

        .dark .bg-emerald-50 { background-color: #022c22 !important; }
        .dark .bg-emerald-100 { background-color: #065f46 !important; }
        .dark .text-emerald-600 { color: #6ee7b7 !important; }
        .dark .text-emerald-700 { color: #6ee7b7 !important; }
        .dark .text-emerald-800 { color: #a7f3d0 !important; }

        .dark .bg-amber-50 { background-color: #451a03 !important; }
        .dark .bg-amber-100 { background-color: #78350f !important; }
        .dark .text-amber-600 { color: #fcd34d !important; }
        .dark .text-amber-700 { color: #fcd34d !important; }
        .dark .text-amber-800 { color: #fde68a !important; }

        .dark .bg-red-50 { background-color: #450a0a !important; }
        .dark .bg-red-100 { background-color: #7f1d1d !important; }
        .dark .text-red-500 { color: #fca5a5 !important; }
        .dark .text-red-600 { color: #fca5a5 !important; }
        .dark .text-red-700 { color: #fca5a5 !important; }
        .dark .text-red-800 { color: #fecaca !important; }

        .dark .bg-blue-50 { background-color: #0c2233 !important; }
        .dark .bg-blue-100 { background-color: #1e3a5f !important; }
        .dark .text-blue-600 { color: #93c5fd !important; }
        .dark .text-blue-700 { color: #93c5fd !important; }
        .dark .text-blue-800 { color: #bfdbfe !important; }

        .dark .bg-purple-50 { background-color: #2e1065 !important; }
        .dark .bg-purple-100 { background-color: #4c1d95 !important; }
        .dark .text-purple-600 { color: #c4b5fd !important; }
        .dark .text-purple-700 { color: #c4b5fd !important; }

        .dark .bg-pink-50 { background-color: #4a0e2b !important; }
        .dark .bg-pink-100 { background-color: #831843 !important; }
        .dark .text-pink-600 { color: #f9a8d4 !important; }
        .dark .text-pink-700 { color: #f9a8d4 !important; }

        .dark .bg-violet-50 { background-color: #2e1065 !important; }
        .dark .bg-violet-100 { background-color: #4c1d95 !important; }
        .dark .text-violet-600 { color: #c4b5fd !important; }
        .dark .text-violet-700 { color: #c4b5fd !important; }

        .dark .bg-rose-50 { background-color: #4c0519 !important; }
        .dark .bg-rose-100 { background-color: #881337 !important; }
        .dark .text-rose-600 { color: #fda4af !important; }
        .dark .text-rose-700 { color: #fda4af !important; }

        .dark .bg-fuchsia-50 { background-color: #3b0764 !important; }
        .dark .bg-fuchsia-100 { background-color: #581c87 !important; }
        .dark .text-fuchsia-600 { color: #f0abfc !important; }
        .dark .text-fuchsia-700 { color: #f0abfc !important; }

        .dark .bg-teal-50 { background-color: #0d2f2c !important; }
        .dark .bg-teal-100 { background-color: #115e59 !important; }
        .dark .text-teal-600 { color: #5eead4 !important; }

        .dark .bg-cyan-50 { background-color: #08263b !important; }
        .dark .bg-cyan-100 { background-color: #155e75 !important; }
        .dark .text-cyan-600 { color: #67e8f9 !important; }

        .dark .text-green-600 { color: #86efac !important; }
        .dark .text-orange-600 { color: #fdba74 !important; }
        .dark .text-lime-600 { color: #a3e635 !important; }
        .dark .text-yellow-600 { color: #fde047 !important; }

        .dark .ring-gray-200 { --tw-ring-color: #334155 !important; }
        .dark .divide-gray-100 > * + * { border-color: #334155 !important; }
        .dark .divide-gray-200 > * + * { border-color: #475569 !important; }

        .dark input:not([type="checkbox"]):not([type="radio"]),
        .dark select,
        .dark textarea {
            color: #e2e8f0 !important;
            background-color: #1e293b !important;
        }
        .dark select option {
            color: #e2e8f0 !important;
            background-color: #1e293b !important;
        }
        .dark input::placeholder,
        .dark textarea::placeholder {
            color: #64748b !important;
            opacity: 1 !important;
        }
        .dark .appearance-none { color: #e2e8f0 !important; }
    </style>
</head>
<body class="font-sans antialiased min-h-screen">
    <nav class="nav-gradient shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="text-xl font-bold text-white flex items-center gap-2 flex-shrink-0">
                    @if ($storeLogo)
                        <div class="w-8 h-8 rounded-lg overflow-hidden flex-shrink-0 bg-white/20 flex items-center justify-center">
                            <img src="{{ $storeLogo }}?v={{ time() }}" alt="" class="w-full h-full object-cover">
                        </div>
                    @else
                        <span class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center text-sm flex-shrink-0">{{ substr($storeName, 0, 1) }}</span>
                    @endif
                    <span class="truncate max-w-[160px]">{{ $storeName }}</span>
                </a>
                <button id="navbarToggle" class="lg:hidden p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 focus:outline-none" aria-label="Toggle menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div id="navbarNav" class="hidden lg:flex lg:items-center lg:gap-1 absolute lg:static top-16 left-0 right-0 lg:bg-transparent bg-white border-b lg:border-0 border-gray-200 px-4 lg:px-0 pb-4 lg:pb-0 z-50 shadow-lg lg:shadow-none rounded-b-2xl lg:rounded-none dark:bg-slate-800">
                    <ul class="flex flex-col lg:flex-row lg:items-center gap-1 lg:mr-auto">
                        @auth
                            <li><a href="{{ route('transactions.pos') }}" class="block px-3 py-2 rounded-lg lg:text-white/80 text-gray-700 lg:hover:text-white hover:text-indigo-600 lg:hover:bg-white/10 hover:bg-indigo-50 transition-all dark:text-slate-200 dark:hover:bg-slate-700">POS</a></li>
                            <li><a href="{{ route('products.index') }}" class="block px-3 py-2 rounded-lg lg:text-white/80 text-gray-700 lg:hover:text-white hover:text-indigo-600 lg:hover:bg-white/10 hover:bg-indigo-50 transition-all dark:text-slate-200 dark:hover:bg-slate-700">{{ __('Products') }}</a></li>
                            <li><a href="{{ route('transactions.index') }}" class="block px-3 py-2 rounded-lg lg:text-white/80 text-gray-700 lg:hover:text-white hover:text-indigo-600 lg:hover:bg-white/10 hover:bg-indigo-50 transition-all dark:text-slate-200 dark:hover:bg-slate-700">{{ __('History') }}</a></li>
                            @if (auth()->user()->role === 'admin')
                                <li><a href="{{ route('dashboard.admin') }}" class="block px-3 py-2 rounded-lg lg:text-white/80 text-gray-700 lg:hover:text-white hover:text-indigo-600 lg:hover:bg-white/10 hover:bg-indigo-50 transition-all dark:text-slate-200 dark:hover:bg-slate-700">{{ __('Dashboard') }}</a></li>
                                <li><a href="{{ route('members.index') }}" class="block px-3 py-2 rounded-lg lg:text-white/80 text-gray-700 lg:hover:text-white hover:text-indigo-600 lg:hover:bg-white/10 hover:bg-indigo-50 transition-all dark:text-slate-200 dark:hover:bg-slate-700">{{ __('Members') }}</a></li>
                                <li><a href="{{ route('users.index') }}" class="block px-3 py-2 rounded-lg lg:text-white/80 text-gray-700 lg:hover:text-white hover:text-indigo-600 lg:hover:bg-white/10 hover:bg-indigo-50 transition-all dark:text-slate-200 dark:hover:bg-slate-700">{{ __('Users') }}</a></li>
                                <li><a href="{{ route('letters.index') }}" class="block px-3 py-2 rounded-lg lg:text-white/80 text-gray-700 lg:hover:text-white hover:text-indigo-600 lg:hover:bg-white/10 hover:bg-indigo-50 transition-all dark:text-slate-200 dark:hover:bg-slate-700"><i class="bi bi-file-text me-1"></i>{{ __('Letters') }}</a></li>
                            @endif
                        @endauth
                    </ul>
                    <ul class="flex flex-col lg:flex-row lg:items-center gap-1 mt-2 lg:mt-0 pt-2 lg:pt-0 border-t lg:border-0 border-gray-200 lg:border-white/10">
                        @auth
                            <li class="notification-dropdown relative">
                                <button class="notification-toggle flex items-center gap-2 px-3 py-2 rounded-lg lg:text-white/80 text-gray-700 lg:hover:text-white hover:text-indigo-600 lg:hover:bg-white/10 hover:bg-indigo-50 transition-all w-full lg:w-auto relative dark:text-slate-200 dark:hover:bg-slate-700">
                                    <i class="bi bi-bell text-lg"></i>
                                    @if ($lowStockCount > 0)
                                        <span class="notification-badge absolute -top-0.5 -right-0.5 lg:right-0.5 inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full border-2 border-white">{{ $lowStockCount > 9 ? '9+' : $lowStockCount }}</span>
                                    @endif
                                    <span class="lg:hidden text-sm">{{ __('Notifications') }}</span>
                                </button>
                                <div class="notification-menu hidden absolute right-0 mt-1 w-72 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50 max-h-96 overflow-y-auto dark:bg-slate-800 dark:border-slate-700">
                                    @if ($lowStockCount > 0)
                                        <div class="px-4 py-2 border-b border-gray-100 dark:border-slate-700">
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-slate-400">{{ __('Low Stock') }}</p>
                                        </div>
                                        @foreach ($lowStockList as $item)
                                        <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-amber-50 transition-all dark:hover:bg-slate-700">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $item->stock <= 0 ? 'bg-red-100 text-red-600' : ($item->stock <= 5 ? 'bg-red-50 text-red-500' : 'bg-amber-50 text-amber-600') }}">
                                                <i class="bi {{ $item->stock <= 0 ? 'bi-x-circle' : 'bi-exclamation-triangle' }}"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-800 truncate dark:text-slate-200">{{ $item->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-slate-400">{{ __('Stock label') }}: <span class="font-semibold {{ $item->stock <= 0 ? 'text-red-600' : ($item->stock <= 5 ? 'text-red-500' : 'text-amber-600') }}">{{ $item->stock }}</span></p>
                                            </div>
                                            <span class="text-xs text-gray-400 dark:text-slate-500">{{ $item->stock <= 0 ? __('Out of Stock') : ($item->stock <= 5 ? __('Critical') : __('Low')) }}</span>
                                        </a>
                                        @endforeach
                                        <div class="px-4 py-2 border-t border-gray-100 dark:border-slate-700">
                                            <a href="{{ route('products.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium dark:text-indigo-400">{{ __('View All') }} →</a>
                                        </div>
                                    @else
                                        <div class="px-4 py-6 text-center text-gray-400 dark:text-slate-500">
                                            <i class="bi bi-check2-circle text-3xl"></i>
                                            <p class="text-sm mt-1">{{ __('No notifications') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </li>
                            <li class="dropdown relative">
                                <button class="dropdown-toggle flex items-center gap-2 px-3 py-2 rounded-lg lg:text-white/80 text-gray-700 lg:hover:text-white hover:text-indigo-600 lg:hover:bg-white/10 hover:bg-indigo-50 transition-all w-full lg:w-auto dark:text-slate-200 dark:hover:bg-slate-700">
                                    <i class="bi bi-person-circle text-lg"></i>
                                    <span class="truncate">{{ auth()->user()->name }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ auth()->user()->role === 'admin' ? 'lg:bg-amber-400/20 lg:text-amber-300 bg-amber-100 text-amber-700' : 'lg:bg-emerald-400/20 lg:text-emerald-300 bg-emerald-100 text-emerald-700' }}">
                                        {{ auth()->user()->role === 'admin' ? __('Admin') : __('Cashier') }}
                                    </span>
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <ul class="dropdown-menu hidden absolute right-0 mt-1 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50 dark:bg-slate-800 dark:border-slate-700">
                                    <li>
                                        <a href="javascript:void(0)" onclick="toggleDarkMode()" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-all dark:text-slate-200 dark:hover:bg-slate-700">
                                            <i class="bi {{ session('_dark_mode', false) ? 'bi-sun' : 'bi-moon' }}" id="darkModeIcon"></i>
                                            <span id="darkModeLabel">{{ session('_dark_mode', false) ? __('Light Mode') : __('Dark Mode') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('language.switch', app()->getLocale() === 'id' ? 'en' : 'id') }}" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-all dark:text-slate-200 dark:hover:bg-slate-700">
                                            <i class="bi bi-translate"></i>
                                            {{ app()->getLocale() === 'id' ? 'English' : 'Bahasa Indonesia' }}
                                        </a>
                                    </li>
                                    @if (auth()->user()->role === 'admin')
                                    <li><hr class="mx-3 border-gray-100 dark:border-slate-700"></li>
                                    <li>
                                        <a href="{{ route('settings.qris') }}" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-all dark:text-slate-200 dark:hover:bg-slate-700">
                                            <i class="bi bi-qr-code"></i> {{ __('QRIS Settings') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('settings.points') }}" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition-all dark:text-slate-200 dark:hover:bg-slate-700">
                                            <i class="bi bi-star"></i> {{ __('Points Settings') }}
                                        </a>
                                    </li>
                                    <li><hr class="mx-3 border-gray-100 dark:border-slate-700"></li>
                                    <li>
                                        <a href="{{ route('settings.store') }}" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-all dark:text-slate-200 dark:hover:bg-slate-700">
                                            <i class="bi bi-shop"></i> {{ __('Store Settings') }}
                                        </a>
                                    </li>
                                    <li><hr class="mx-3 border-gray-100 dark:border-slate-700"></li>
                                    @endif
                                    <li>
                                        <button onclick="logout()" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-all dark:text-red-400 dark:hover:bg-red-900/30">
                                            <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                                        </button>
                                    </li>
                                </ul>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 dark:text-slate-200">
        @if (session('success'))
            <div class="alert flex items-center justify-between p-4 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 shadow-sm card-hover dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-400">
                <span><i class="bi bi-check-circle-fill text-emerald-500 me-2"></i>{{ session('success') }}</span>
                <button data-dismiss="alert" class="text-emerald-500 hover:text-emerald-700 dark:text-emerald-400 text-xl leading-none">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert p-4 mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 shadow-sm dark:bg-red-900/30 dark:border-red-800 dark:text-red-400">
                <div class="flex items-start gap-2">
                    <i class="bi bi-exclamation-circle-fill text-red-500 mt-0.5"></i>
                    <ul class="mb-0 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button data-dismiss="alert" class="text-red-500 hover:text-red-700 dark:text-red-400 float-right -mt-7 text-xl leading-none">&times;</button>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
    var _darkMode = document.querySelector('meta[name="dark-mode"]').content === '1';

    function applyDarkMode(dark) {
        if (dark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        var icon = document.getElementById('darkModeIcon');
        var label = document.getElementById('darkModeLabel');
        if (icon) icon.className = 'bi ' + (dark ? 'bi-sun' : 'bi-moon');
        if (label) label.textContent = dark ? '{{ __("Light Mode") }}' : '{{ __("Dark Mode") }}';
    }

    function toggleDarkMode() {
        _darkMode = !_darkMode;
        localStorage.setItem('darkMode', _darkMode ? '1' : '0');
        applyDarkMode(_darkMode);
        fetch('{{ url("dark-mode") }}/' + (_darkMode ? '1' : '0'), {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        });
    }

    var stored = localStorage.getItem('darkMode');
    if (stored !== null) {
        _darkMode = stored === '1';
        applyDarkMode(_darkMode);
    }

    function openModal(id) {
        var el = document.getElementById(id);
        if (el) { el.classList.remove('hidden'); el.classList.add('flex'); document.body.style.overflow = 'hidden'; }
    }
    function closeModal(id) {
        var el = document.getElementById(id);
        if (el) { el.classList.add('hidden'); el.classList.remove('flex'); document.body.style.overflow = ''; }
    }
    function logout() {
        if (!confirm('{{ __("Are you sure") }}?')) return;
        fetch('{{ route("logout") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        }).then(function() { window.location.reload(); });
    }
    </script>
    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>