<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/language/{locale}', function ($locale) {
    if (!in_array($locale, ['id', 'en'])) $locale = 'id';
    session(['locale' => $locale]);
    if (auth()->check()) {
        App\Models\Setting::set('user_' . auth()->id() . '_locale', $locale);
    }
    return redirect()->back();
})->name('language.switch');

Route::get('/dark-mode/{value}', function ($value) {
    $darkMode = $value === '1';
    if (auth()->check()) {
        App\Models\Setting::set('user_' . auth()->id() . '_dark_mode', $darkMode ? '1' : '0');
    }
    return redirect()->back();
})->name('dark-mode.switch');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('dashboard.admin');
        }
        return redirect()->route('transactions.pos');
    });

    Route::resource('products', ProductController::class)->middleware('role:admin,kasir');

    Route::prefix('transactions')->name('transactions.')->middleware('role:admin,kasir')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/pos', [TransactionController::class, 'pos'])->name('pos');
        Route::get('/search-products', [TransactionController::class, 'searchProducts'])->name('search-products');
        Route::post('/register-member', [TransactionController::class, 'registerMember'])->name('register-member');
        Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout');
        Route::get('/export', [TransactionController::class, 'export'])->name('export');
        Route::get('/export-pdf', [TransactionController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('receipt');
        Route::get('/{transaction}/receipt/pdf', [TransactionController::class, 'receiptPdf'])->name('receipt-pdf');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
    });

    Route::prefix('members')->name('members.')->middleware('role:admin,kasir')->group(function () {
        Route::get('/search', [MemberController::class, 'search'])->name('search');
        Route::get('/search-by-phone', [MemberController::class, 'searchByPhone'])->name('search-by-phone');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard.admin');

        Route::resource('members', MemberController::class);

        Route::get('/users', [DashboardController::class, 'users'])->name('users.index');
        Route::get('/users/create', [DashboardController::class, 'createUser'])->name('users.create');
        Route::post('/users', [DashboardController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [DashboardController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [DashboardController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [DashboardController::class, 'destroyUser'])->name('users.destroy');

        Route::get('/settings/qris', [DashboardController::class, 'qrisSettings'])->name('settings.qris');
        Route::post('/settings/qris/upload', [DashboardController::class, 'qrisUpload'])->name('settings.qris.upload');

        Route::get('/settings/points', [DashboardController::class, 'pointsSettings'])->name('settings.points');
        Route::post('/settings/points', [DashboardController::class, 'pointsUpdate'])->name('settings.points.update');
    });
});
