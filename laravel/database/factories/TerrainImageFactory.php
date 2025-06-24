<?php

namespace Database\Factories;

use App\Models\Terrain;
use Illuminate\Database\Eloquent\Factories\Factory;

class TerrainImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'terrain_id' => Terrain::factory(),
            'image_path' => fake()->imageUrl(640, 480, 'nature'),
            'uploaded_at' => fake()->dateTimeThisYear(),
        ];
    }
}
