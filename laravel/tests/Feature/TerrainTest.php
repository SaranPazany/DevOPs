<?php

use App\Models\User;
use App\Models\Terrain;

test('can create terrain', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/terrains', [
        'title' => 'Test Terrain',
        'description' => 'A beautiful terrain for camping',
        'location' => 'Test Location',
        'area_size' => 1000.50,
        'price_per_day' => 100.00,
        'available_from' => '2025-07-01',
        'available_to' => '2025-12-31',
        'is_available' => true,
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('terrains', [
        'title' => 'Test Terrain',
        'owner_id' => $user->id,
    ]);
});

test('can list terrains', function () {
    Terrain::factory(3)->create();

    $response = $this->getJson('/api/terrains');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => ['id', 'title', 'location', 'price_per_day']
        ]
    ]);
});

test('can show terrain', function () {
    $terrain = Terrain::factory()->create();

    $response = $this->getJson("/api/terrains/{$terrain->id}");

    $response->assertStatus(200);
    $response->assertJson([
        'id' => $terrain->id,
        'title' => $terrain->title,
    ]);
});

test('can update terrain', function () {
    $user = User::factory()->create();
    $terrain = Terrain::factory()->create(['owner_id' => $user->id]);

    $response = $this->actingAs($user)->putJson("/api/terrains/{$terrain->id}", [
        'title' => 'Updated Terrain',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('terrains', [
        'id' => $terrain->id,
        'title' => 'Updated Terrain',
    ]);
});

test('can delete terrain', function () {
    $user = User::factory()->create();
    $terrain = Terrain::factory()->create(['owner_id' => $user->id]);

    $response = $this->actingAs($user)->deleteJson("/api/terrains/{$terrain->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('terrains', ['id' => $terrain->id]);
});
