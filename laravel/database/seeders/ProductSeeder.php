<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => "PS1",
            'category_id' => 1,
            'pricing' => 100,
        ]);
        Product::create([
            'name' => "PS2",
            'category_id' => 1,
            'pricing' => 250,
        ]);
        Product::create([
            'name' => "Switch",
            'category_id' => 2,
            'pricing' => 2000,
        ]);
    }
}
