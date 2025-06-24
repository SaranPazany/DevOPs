<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Terrain;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $bookings = Booking::with(['terrain', 'renter'])
            ->where('renter_id', $request->user()->id)
            ->paginate(10);

        return response()->json($bookings);
    }

    public function store(StoreBookingRequest $request)
    {
        $terrain = Terrain::findOrFail($request->terrain_id);

        $days = \Carbon\Carbon::parse($request->start_date)
            ->diffInDays(\Carbon\Carbon::parse($request->end_date));

        $booking = Booking::create([
            ...$request->validated(),
            'renter_id' => $request->user()->id,
            'total_price' => $terrain->price_per_day * $days,
        ]);

        return response()->json($booking->load(['terrain', 'renter']), 201);
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        return response()->json($booking->load(['terrain', 'renter', 'payments']));
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $booking->update($request->validated());
        return response()->json($booking->load(['terrain', 'renter']));
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);
        $booking->delete();
        return response()->json(null, 204);
    }
}
