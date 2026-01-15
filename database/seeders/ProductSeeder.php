<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Nasi Goreng',
                'description' => 'Nasi goreng spesial dengan telur',
                'price' => 15000,
                'stock' => 50,
                'category' => 'food',
                'photo' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Mie Goreng',
                'description' => 'Mie goreng pedas',
                'price' => 12000,
                'stock' => 40,
                'category' => 'food',
                'photo' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Ayam Bakar',
                'description' => 'Ayam bakar dengan sambal',
                'price' => 20000,
                'stock' => 30,
                'category' => 'food',
                'photo' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Es Teh Manis',
                'description' => 'Es teh manis segar',
                'price' => 5000,
                'stock' => 100,
                'category' => 'drink',
                'photo' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Es Jeruk',
                'description' => 'Es jeruk peras segar',
                'price' => 7000,
                'stock' => 80,
                'category' => 'drink',
                'photo' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Kopi Hitam',
                'description' => 'Kopi hitam original',
                'price' => 8000,
                'stock' => 60,
                'category' => 'drink',
                'photo' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Keripik Singkong',
                'description' => 'Keripik singkong renyah',
                'price' => 10000,
                'stock' => 70,
                'category' => 'snack',
                'photo' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Pisang Goreng',
                'description' => 'Pisang goreng crispy',
                'price' => 8000,
                'stock' => 50,
                'category' => 'snack',
                'photo' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Tahu Isi',
                'description' => 'Tahu isi sayuran',
                'price' => 6000,
                'stock' => 60,
                'category' => 'snack',
                'photo' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Bakso',
                'description' => 'Bakso sapi dengan mie',
                'price' => 18000,
                'stock' => 35,
                'category' => 'food',
                'photo' => null,
                'status' => 'active',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
