<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'kasir',
        ]);

        $categories = ['Makanan', 'Minuman', 'Snack', 'Sembako'];
        $products = [
            ['Nasi Goreng', 15000, 10000, 50, 'Makanan'],
            ['Mie Goreng', 12000, 8000, 40, 'Makanan'],
            ['Ayam Geprek', 18000, 12000, 30, 'Makanan'],
            ['Es Teh Manis', 5000, 2000, 100, 'Minuman'],
            ['Es Jeruk', 6000, 2500, 80, 'Minuman'],
            ['Kopi Hitam', 7000, 3000, 60, 'Minuman'],
            ['Kopi Susu', 10000, 5000, 60, 'Minuman'],
            ['Pisang Goreng', 8000, 4000, 45, 'Snack'],
            ['Tahu Isi', 7000, 3000, 40, 'Snack'],
            ['Kentang Goreng', 12000, 6000, 35, 'Snack'],
            ['Beras 5kg', 75000, 70000, 20, 'Sembako'],
            ['Minyak Goreng 1L', 18000, 16000, 25, 'Sembako'],
            ['Gula Pasir 1kg', 15000, 13000, 30, 'Sembako'],
            ['Telur 1kg', 28000, 25000, 15, 'Sembako'],
        ];

        foreach ($products as $p) {
            Product::create([
                'name' => $p[0],
                'selling_price' => $p[1],
                'purchase_price' => $p[2],
                'stock' => $p[3],
                'category' => $p[4],
            ]);
        }
    }
}
