<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('booking.terrain')->paginate(10);
        return response()->json($payments);
    }

    public function store(StorePaymentRequest $request)
    {
        $payment = Payment::create($request->validated());
        return response()->json($payment->load('booking'), 201);
    }

    public function show(Payment $payment)
    {
        return response()->json($payment->load('booking.terrain'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $payment->update($request->validated());
        return response()->json($payment->load('booking'));
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json(null, 204);
    }
}
