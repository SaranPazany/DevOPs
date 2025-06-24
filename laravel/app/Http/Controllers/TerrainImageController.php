<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTerrainImageRequest;
use App\Models\TerrainImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TerrainImageController extends Controller
{
    public function store(StoreTerrainImageRequest $request)
    {
        $images = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('terrain-images', 'public');

            $terrainImage = TerrainImage::create([
                'terrain_id' => $request->terrain_id,
                'image_path' => $path,
            ]);

            $images[] = $terrainImage;
        }

        return response()->json($images, 201);
    }

    public function destroy(TerrainImage $terrainImage)
    {
        Storage::disk('public')->delete($terrainImage->image_path);
        $terrainImage->delete();

        return response()->json(null, 204);
    }
}
