<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::factory(50)->create()->each(function ($booking) {
            if (rand(1, 100) <= 70) { // 70% chance of having a payment
                Payment::factory()->create([
                    'booking_id' => $booking->id,
                    'amount_paid' => $booking->total_price,
                ]);
            }
        });
    }
}
