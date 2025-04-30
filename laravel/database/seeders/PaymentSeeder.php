<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Payment::create([
            'payment_method' => "ABA",
            'amount' => 9.10,
            'order_id' => 1,
            'customer_id' => 2,
            'payment_date' => '10/6/2023 13:40:03',
        ]);

        Payment::create([
            'payment_method' => "Cash",
            'amount' => 5.05,
            'order_id' => 2,
            'customer_id' => 1,
            'payment_date' => '10/6/2023 00:00:03',
        ]);
    }
}
