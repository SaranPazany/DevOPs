<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReviewController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $reviews = Review::with(['terrain', 'user'])->paginate(10);
        return response()->json($reviews);
    }

    public function store(StoreReviewRequest $request)
    {
        $review = Review::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return response()->json($review->load(['terrain', 'user']), 201);
    }

    public function show(Review $review)
    {
        return response()->json($review->load(['terrain', 'user']));
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        $review->update($request->validated());
        return response()->json($review->load(['terrain', 'user']));
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        $review->delete();
        return response()->json(null, 204);
    }
}
