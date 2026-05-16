<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale');

        if (!$locale && $request->user()) {
            $locale = Setting::get('user_' . $request->user()->id . '_locale');
        }

        $locale = $locale ?: 'id';
        app()->setLocale($locale);
        session(['locale' => $locale]);

        $darkMode = session('_dark_mode');
        if ($darkMode === null && $request->user()) {
            $darkMode = Setting::get('user_' . $request->user()->id . '_dark_mode', '0') === '1';
            session(['_dark_mode' => $darkMode]);
        }

        return $next($request);
    }
}
