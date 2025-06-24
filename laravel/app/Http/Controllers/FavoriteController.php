<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFavoriteRequest;
use App\Models\Favorite;
use App\Models\Terrain;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = Favorite::with('terrain')
            ->where('user_id', $request->user()->id)
            ->paginate(10);

        return response()->json($favorites);
    }

    public function store(StoreFavoriteRequest $request)
    {
        $favorite = Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'terrain_id' => $request->terrain_id,
        ]);

        return response()->json($favorite->load('terrain'), 201);
    }

    public function destroy(Request $request, Terrain $terrain)
    {
        Favorite::where([
            'user_id' => $request->user()->id,
            'terrain_id' => $terrain->id,
        ])->delete();

        return response()->json(null, 204);
    }
}
