<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    // Store a newly created payment in the database
    public function store(Request $request, $saleId)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            // Add any other necessary validation rules here
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $sale = Sale::findOrFail($saleId);

        $payment = new Payment();
        $payment->sale_id = $sale->id;
        $payment->amount = $request->amount;
        // You may want to add additional fields such as payment method, date, etc.
        $payment->save();

        return response()->json($payment, 201);
    }

    // Display the specified payment
    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        return response()->json($payment);
    }

    // Remove the specified payment from the database
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully'], 200);
    }
}