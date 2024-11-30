<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        $customer = Customer::create($request->all());
        return response()->json($customer, 201);
    }

    /**
     * Display the specified customer.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return response()->json($customer);
    }

    /**
     * Update the specified customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        $customer->update($request->all());
        return response()->json($customer);
    }

    /**
     * Remove the specified customer from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json(null, 204);
    }
    public function buyProperty(Request $request, $propertyId)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
        ]);
    
        $property = Property::findOrFail($propertyId);
    
        if ($property->status !== 'available' || $property->propertyUse !== 'sale') {
            return response()->json(['message' => 'Property is not available for sale'], 400);
        }
    
        DB::beginTransaction();
    
        try {
            // Create transaction using TransactionController
            $transactionController = new TransactionController();
            $transaction = $transactionController->store(new Request([
                'property_id' => $property->id,
                'customer_id' => $request->customer_id,
            ]));
    
            // chappa Api integration here
    
            DB::commit();
    
            return response()->json([
                'message' => 'Property purchased successfully',
                'transaction' => $transaction->original
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred during the purchase'], 500);
        }
    }
    
    public function rentProperty(Request $request, $propertyId)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'rent_start_date' => 'required|date',
            'rent_end_date' => 'required|date|after:rent_start_date',
        ]);
    
        $property = Property::findOrFail($propertyId);
    
        if ($property->status !== 'available' || $property->propertyUse !== 'rent') {
            return response()->json(['message' => 'Property is not available for rent'], 400);
        }
    
        DB::beginTransaction();
    
        try {
            // Create transaction using TransactionController
            $transactionController = new TransactionController();
            $transaction = $transactionController->store(new Request([
                'property_id' => $property->id,
                'customer_id' => $request->customer_id,
                'rent_start_date' => $request->rent_start_date,
                'rent_end_date' => $request->rent_end_date,
            ]));
    
            DB::commit();
    
            return response()->json([
                'message' => 'Property rented successfully',
                'transaction' => $transaction->original
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred during the rental process'], 500);
        }
    }
}
