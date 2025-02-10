<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\User;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\TransactionResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['property'])->latest()->paginate(10);
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

            $transaction = $this->createTransaction($property, $owner, $validatedData);

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

    private function createTransaction(Property $property, Owner $owner, array $data)
    {
        $commission = $this->calculateCommission($property);

        $transaction = Transaction::create([
            'property_id' => $property->id,
            'owner_id' => $owner->id,
            'customer_id' => $data['customer_id'] ?? null,
            'transaction_type' => $property->propertyUse,
            'transaction_date' => now(),
            'price' => $property->price,
            'commission' => $commission,
            'rent_start_date' => $property->propertyUse === 'rent' ? $data['rent_start_date'] : null,
            'rent_end_date' => $property->propertyUse === 'rent' ? $data['rent_end_date'] : null,
        ]);

        // Update property status
        $property->update([
            'status' => $property->propertyUse === 'sale' ? 'sold' : 'rented'
        ]);

        return $transaction;
    }

    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction->load(['property', 'customer', 'owner']));
    }

    private function calculateCommission(Property $property)
    {
        if ($property->property_use === 'sale') {
            return $property->price * 0.10;
        }
        return 50.00; // Fixed commission for rentals
    }

    public function getTransactions()
    {
        try {
            $transactions = Transaction::with(['property', 'customer', 'owner'])->get(); 

            return response()->json($transactions);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error fetching transactions: ' . $e->getMessage());

            return response()->json(['error' => 'Unable to fetch transactions'], 500);
        }
    }

    public function getSaleTransactionsByManager()
    {
        try {
            $manager_id = 3;
            $manager = Manager::findOrFail($manager_id);

            // Get properties in manager's region first
            $propertyIds = Property::where('region_id', $manager->region_id)
                                 ->pluck('id');

            // Then get transactions for those properties
            $transactions = Transaction::with(['property', 'customer', 'owner'])
                ->where('transaction_type', 'sale')
                ->whereIn('property_id', $propertyIds)
                ->get();

            return TransactionResource::collection($transactions);
        } catch (\Exception $e) {
            Log::error('Error fetching sale transactions: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch sale transactions'], 500);
        }
    }

    public function getRentTransactionsByManager()
    {
        try {
            // Get the authenticated user using the 'manager' guard
            $manager = auth('manager')->user();
              Log::debug($manager);
            // Ensure the authenticated user is a manager
            if (!$manager) {
                Log::error('Unauthorized access: User is not a manager.');
                return response()->json(['error' => 'Unauthorized'], 403);
            }
    
            // Get property IDs in the manager's region
            $propertyIds = Property::where('region_id', $manager->region_id)->pluck('id');
    
            // Get transactions for properties in that region
            $transactions = Transaction::with(['property', 'customer', 'owner'])
                ->where('transaction_type', 'rent')
                ->whereIn('property_id', $propertyIds)
                ->get();
    
            return TransactionResource::collection($transactions);
        } catch (\Exception $e) {
            Log::error('Error fetching rent transactions: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch rent transactions'], 500);
        }
    }
}
