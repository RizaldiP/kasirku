<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useTailwind();

        View::composer('layouts.app', function ($view) {
            $storeName = Setting::get('store_name', 'Kasirku');
            $storeLogo = file_exists(public_path('storage/logo.png')) ? asset('storage/logo.png') : null;
            $lowStockList = Product::where('stock', '<', 10)->orderBy('stock')->take(5)->get(['id', 'name', 'stock']);
            $lowStockCount = count($lowStockList);
            $view->with(compact('storeName', 'storeLogo', 'lowStockCount', 'lowStockList'));
        });
    }
}
