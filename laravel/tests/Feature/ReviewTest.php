<?php

use App\Models\User;
use App\Models\Terrain;
use App\Models\Review;

test('can create review', function () {
    $user = User::factory()->create();
    $terrain = Terrain::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/reviews', [
        'terrain_id' => $terrain->id,
        'rating' => 5,
        'comment' => 'Amazing terrain!',
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('reviews', [
        'terrain_id' => $terrain->id,
        'user_id' => $user->id,
        'rating' => 5,
    ]);
});

test('validates rating between 1 and 5', function () {
    $user = User::factory()->create();
    $terrain = Terrain::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/reviews', [
        'terrain_id' => $terrain->id,
        'rating' => 6, // Invalid rating
        'comment' => 'Test comment',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['rating']);
});

test('can update review', function () {
    $user = User::factory()->create();
    $review = Review::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->putJson("/api/reviews/{$review->id}", [
        'rating' => 4,
        'comment' => 'Updated comment',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('reviews', [
        'id' => $review->id,
        'rating' => 4,
        'comment' => 'Updated comment',
    ]);
});
