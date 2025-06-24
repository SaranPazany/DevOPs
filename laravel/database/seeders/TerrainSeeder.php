<?php

namespace Database\Seeders;

use App\Models\Terrain;
use App\Models\TerrainImage;
use Illuminate\Database\Seeder;

class TerrainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Terrain::factory(20)->create()->each(function ($terrain) {
            TerrainImage::factory(rand(1, 5))->create([
                'terrain_id' => $terrain->id
            ]);
        });
    }
}
