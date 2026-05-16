<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalRevenue = Transaction::sum('total_price');
        $totalTransactions = Transaction::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();

        $todayRevenue = Transaction::whereDate('created_at', today())->sum('total_price');
        $todayTransactions = Transaction::whereDate('created_at', today())->count();

        $recentTransactions = Transaction::with('cashier', 'details.product')
            ->latest()
            ->take(5)
            ->get();

        $topProducts = DB::table('transaction_details')
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_subtotal'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get()
            ->map(fn($item) => [
                'product' => Product::withTrashed()->find($item->product_id),
                'total_qty' => $item->total_qty,
                'total_subtotal' => $item->total_subtotal,
            ]);

        $dailyLabels = [];
        $dailyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyLabels[] = $date->format('d M');
            $dailyData[] = (float) Transaction::whereDate('created_at', $date)->sum('total_price');
        }

        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonthsNoOverflow($i);
            $monthlyLabels[] = $date->format('M Y');
            $monthlyData[] = (float) Transaction::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_price');
        }

        $lowStock = Product::where('stock', '<', 10)->orderBy('stock')->get();

        return view('dashboard.admin', compact(
            'totalRevenue', 'totalTransactions', 'totalProducts', 'totalUsers',
            'todayRevenue', 'todayTransactions',
            'recentTransactions', 'topProducts',
            'dailyLabels', 'dailyData',
            'monthlyLabels', 'monthlyData',
            'lowStock',
        ));
    }

    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function createUser()
    {
        return view('users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,kasir',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,kasir',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus akun sendiri.']);
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    public function qrisSettings()
    {
        $qrisExists = file_exists(public_path('storage/qris.png'));
        return view('settings.qris', compact('qrisExists'));
    }

    public function qrisUpload(Request $request)
    {
        $request->validate([
            'qris_image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $file = $request->file('qris_image');
        $file->move(public_path('storage'), 'qris.png');

        return redirect()->route('settings.qris')->with('success', 'QRIS berhasil diupload.');
    }

    public function pointsSettings()
    {
        $earnPerAmount = Setting::get('points_earn_per_amount', 10000);
        $redeemPerDiscount = Setting::get('points_redeem_per_discount', 100);
        $discountPerUnit = Setting::get('points_discount_per_unit', 2000);
        return view('settings.points', compact('earnPerAmount', 'redeemPerDiscount', 'discountPerUnit'));
    }

    public function pointsUpdate(Request $request)
    {
        $request->validate([
            'earn_per_amount' => 'required|integer|min:100',
            'redeem_per_discount' => 'required|integer|min:1',
            'discount_per_unit' => 'required|integer|min:100',
        ]);

        Setting::set('points_earn_per_amount', $request->earn_per_amount);
        Setting::set('points_redeem_per_discount', $request->redeem_per_discount);
        Setting::set('points_discount_per_unit', $request->discount_per_unit);

        return redirect()->route('settings.points')->with('success', 'Pengaturan poin berhasil disimpan.');
    }
}
