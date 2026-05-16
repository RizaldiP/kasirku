<?php

namespace App\Providers;

use App\Models\Product;
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
            $lowStockCount = Product::where('stock', '<', 10)->count();
            $lowStockList = Product::where('stock', '<', 10)->orderBy('stock')->take(5)->get();
            $view->with(compact('lowStockCount', 'lowStockList'));
        });
    }
}
