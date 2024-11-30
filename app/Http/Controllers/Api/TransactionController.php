<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\TransactionResource;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['property', 'customer', 'owner'])->latest()->paginate(10);
        return TransactionResource::collection($transactions);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'customer_id' => 'required_if:transaction_type,sale|exists:customers,id',
            'rent_start_date' => 'required_if:transaction_type,rent|date|nullable',
            'rent_end_date' => 'required_if:transaction_type,rent|date|nullable|after:rent_start_date',
        ]);

        try {
            DB::beginTransaction();

            $property = Property::findOrFail($validatedData['property_id']);
            $owner = Owner::where('user_id', $property->user_id)->firstOrFail();

            // Calculate commission
            $commission = $this->calculateCommission($property);

            $transaction = Transaction::create([
                'property_id' => $property->id,
                'owner_id' => $owner->id,
                'customer_id' => $validatedData['customer_id'] ?? null,
                'transaction_type' => $property->propertyUse,
                'transaction_date' => now(),
                'price' => $property->price,
                'commission' => $commission,
                'rent_start_date' => $property->propertyUse === 'rent' ? $validatedData['rent_start_date'] : null,
                'rent_end_date' => $property->propertyUse === 'rent' ? $validatedData['rent_end_date'] : null,
            ]);

            // Update property status
            $property->update([
                'status' => $property->propertyUse === 'sale' ? 'sold' : 'rented'
            ]);

            DB::commit();

            return new TransactionResource($transaction);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction->load(['property', 'customer', 'owner']));
    }

    private function calculateCommission(Property $property)
    {
        if ($property->propertyUse === 'sale') {
            return $property->price * 0.10;
        }
        return 50.00; // Fixed commission for rentals
    }
}