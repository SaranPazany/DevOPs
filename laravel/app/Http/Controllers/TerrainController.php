<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTerrainRequest;
use App\Http\Requests\UpdateTerrainRequest;
use App\Models\Terrain;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TerrainController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $terrains = Terrain::with(['owner', 'images'])->paginate(10);
        return response()->json($terrains);
    }

    public function store(StoreTerrainRequest $request)
    {
        $terrain = Terrain::create([
            ...$request->validated(),
            'owner_id' => $request->user()->id,
        ]);

        return response()->json($terrain->load(['owner', 'images']), 201);
    }

    public function show(Terrain $terrain)
    {
        return response()->json($terrain->load(['owner', 'images', 'reviews.user']));
    }

    public function update(UpdateTerrainRequest $request, Terrain $terrain)
    {
        $terrain->update($request->validated());
        return response()->json($terrain->load(['owner', 'images']));
    }

    public function destroy(Terrain $terrain)
    {
        $this->authorize('delete', $terrain);
        $terrain->delete();
        return response()->json(null, 204);
    }
}
