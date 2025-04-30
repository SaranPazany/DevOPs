<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            "name" => "john bruh",
            "email" => "bruh@gmail.com",
            "phone" => "123456789",
            "address" => "Institute of Prison",
        ]);

        Customer::create([
            "name" => "adventure time",
            "email" => "adventure@gmail.com",
            "phone" => "123456789",
            "address" => "Institute of Prison",
        ]);

        Customer::create([
            "name" => "mr dragon",
            "email" => "dragon@gmail.com",
            "phone" => "987456123",
            "address" => "Institute of Prison",
        ]);
    }
}
