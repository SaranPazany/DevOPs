<?php

use App\Models\Booking;
use App\Models\Payment;

test('can create payment', function () {
    $booking = Booking::factory()->create(['total_price' => 500]);

    $response = $this->postJson('/api/payments', [
        'booking_id' => $booking->id,
        'payment_method' => 'credit_card',
        'amount_paid' => 500.00,
        'transaction_id' => 'txn_123456',
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('payments', [
        'booking_id' => $booking->id,
        'amount_paid' => 500.00,
    ]);
});

test('can list payments', function () {
    Payment::factory(3)->create();

    $response = $this->getJson('/api/payments');

    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(3);
});
