<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
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
            $lowStockList = Product::where('stock', '<', 10)->orderBy('stock')->take(5)->get(['id', 'name', 'stock']);
            $lowStockCount = count($lowStockList);
            $view->with(compact('lowStockCount', 'lowStockList'));
        });
    }
}
