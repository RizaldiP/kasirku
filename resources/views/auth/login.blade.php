<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Login') }} - {{ $storeName }}</title>
    @vite('resources/css/app.css')
    <style>
        .dark body { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%) !important; }
        .dark .bg-white\/90 { background-color: #1e293b !important; }
        .dark .bg-gray-50\/50 { background-color: #1e293b !important; }
        .dark .text-gray-700 { color: #cbd5e1 !important; }
        .dark .text-gray-600 { color: #94a3b8 !important; }
        .dark .text-gray-500 { color: #64748b !important; }
        .dark .text-gray-400 { color: #475569 !important; }
        .dark .text-indigo-600 { color: #818cf8 !important; }
        .dark .border-gray-200 { border-color: #334155 !important; }
        .dark .border-gray-300 { border-color: #475569 !important; }
        .dark input { background-color: #1e293b !important; color: #e2e8f0 !important; }
        .dark .shadow-indigo-200 { box-shadow: 0 4px 14px 0 rgba(129,140,248,0.3) !important; }
    </style>
</head>
<body class="font-sans antialiased min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-emerald-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-lg mb-4">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 bg-clip-text text-transparent">{{ $storeName }}</h1>
            <p class="text-gray-500 mt-1 text-sm">{{ __('Sign in to your account') }}</p>
        </div>

        <div class="bg-white/90 backdrop-blur rounded-3xl shadow-2xl border-0 overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

            <div class="p-8">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-5">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Email Address') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input id="email" type="email" class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-gray-200 bg-gray-50/50 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-200 focus:bg-white transition-all duration-200 @error('email') border-red-400 focus:border-red-400 focus:ring-red-200 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="you@example.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Password') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password" type="password" class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-gray-200 bg-gray-50/50 focus:border-purple-400 focus:ring-4 focus:ring-purple-200 focus:bg-white transition-all duration-200 @error('password') border-red-400 focus:border-red-400 focus:ring-red-200 @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 transition" {{ old('remember') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-600 group-hover:text-gray-800 transition-colors">{{ __('Remember Me') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-transparent bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text hover:from-pink-600 hover:to-indigo-600 transition-all duration-300">{{ __('Forgot Password?') }}</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-scale w-full py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-white font-bold text-base shadow-lg shadow-indigo-200 hover:shadow-xl hover:shadow-indigo-300 transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        {{ __('Login') }}
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center mt-8 text-gray-400 text-xs">&copy; {{ date('Y') }} {{ $storeName }}.</p>
    </div>
    <script>
        var _darkMode = localStorage.getItem('darkMode') === '1';
        if (_darkMode) document.documentElement.classList.add('dark');
    </script>
</body>
</html>
