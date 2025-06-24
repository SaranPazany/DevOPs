<?php

use App\Models\User;
use App\Models\Terrain;
use App\Models\Favorite;

test('can add terrain to favorites', function () {
    $user = User::factory()->create();
    $terrain = Terrain::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/favorites', [
        'terrain_id' => $terrain->id,
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('favorites', [
        'user_id' => $user->id,
        'terrain_id' => $terrain->id,
    ]);
});

test('can list user favorites', function () {
    $user = User::factory()->create();
    Favorite::factory(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/favorites');

    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(3);
});

test('can remove terrain from favorites', function () {
    $user = User::factory()->create();
    $terrain = Terrain::factory()->create();
    Favorite::factory()->create([
        'user_id' => $user->id,
        'terrain_id' => $terrain->id,
    ]);

    $response = $this->actingAs($user)->deleteJson("/api/favorites/{$terrain->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('favorites', [
        'user_id' => $user->id,
        'terrain_id' => $terrain->id,
    ]);
});
