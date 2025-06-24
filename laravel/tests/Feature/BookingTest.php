<?php

use App\Models\User;
use App\Models\Terrain;
use App\Models\Booking;

it('can create booking', function () {
    $user = User::factory()->create();
    $terrain = Terrain::factory()->create(['price_per_day' => 100]);

    $response = $this->actingAs($user)->postJson('/api/bookings', [
        'terrain_id' => $terrain->id,
        'start_date' => '2025-07-01',
        'end_date' => '2025-07-05',
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('bookings', [
        'terrain_id' => $terrain->id,
        'renter_id' => $user->id,
        'total_price' => 400, // 4 days * 100
    ]);
});

it('can list user bookings', function () {
    $user = User::factory()->create();
    Booking::factory(3)->create(['renter_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/bookings');

    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(3);
});

it('can show booking', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->create(['renter_id' => $user->id]);

    $response = $this->actingAs($user)->getJson("/api/bookings/{$booking->id}");

    $response->assertStatus(200);
    $response->assertJson(['id' => $booking->id]);
});

it('can update booking status', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->create(['renter_id' => $user->id]);

    $response = $this->actingAs($user)->putJson("/api/bookings/{$booking->id}", [
        'status' => 'cancelled',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'cancelled',
    ]);
});
