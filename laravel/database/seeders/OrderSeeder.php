<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::create([
            "customer_id" => 1,
            "total_price" => 100,
            "order_date" => "20/5/2028 00:00:00",
        ]);

        Order::create([
            "customer_id" => 2,
            "total_price" => 3000000,
        ])->orderDate= "30/4/2027 00:00:00";
    }
}
