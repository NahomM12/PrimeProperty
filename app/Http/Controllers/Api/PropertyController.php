<?php

namespace App\Http\Controllers\Api;

use App\Models\Property;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Resources\PropertyResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with('propertyType');

        // Apply filters
        if ($request->has('property_type_id')) {
            $query->where('property_type_id', $request->property_type_id);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('address')) {
            $query->where('address', 'like', '%' . $request->address . '%');
        }
        if ($request->has('property_use')) {
            $query->where('property_use', $request->property_use);
        }
        $properties = $query->get();
        return PropertyResource::collection($properties);
    }

    public function store(StorePropertyRequest $request)
    {
        $validatedData = $request->validated();
        
        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('images', 'public');
                $images[] = $imagePath;
            }
            $validatedData['images'] = $images;
        }

        // Add field_values if present
        if (isset($validatedData['field_values'])) {
            $validatedData['field_values'] = json_decode($validatedData['field_values'], true);
        }

        DB::beginTransaction();

        try {
            $property = Property::create($validatedData);
            DB::commit();
            
            return new PropertyResource($property->load('propertyType'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error creating property'], 500);
        }
    }
    public function getPropertiesByUse($use)
    {
        Log::debug($use);
        try {
            $properties = Property::where('property_use', $use)->get();
            Log::debug($properties);
            return response()->json($properties);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    
    public function show($id)
    {
        $property = Property::with('propertyType')->findOrFail($id);
        return new PropertyResource($property);
    }
    
    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'address' => 'sometimes|string|max:255',
            'bedrooms' => 'sometimes|integer|min:0',
            'bathrooms' => 'sometimes|integer|min:0',
            'price' => 'sometimes|numeric|min:0',
            'images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|string|in:available,sold,rented',
            'propertyUse' => 'sometimes|string|in:rent,sale',
            'property_type_id' => 'sometimes|exists:property_types,id',
            'field_values' => 'sometimes|array',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
        ]);
    
        DB::beginTransaction();
    
        try {
            // Handle multiple image uploads
            if ($request->hasFile('images')) {
                $images = [];
                // Delete old images if needed
                // Storage::disk('public')->delete($property->images);
                
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('images', 'public');
                    $images[] = $imagePath;
                }
                $validatedData['images'] = $images;
            }
    
            // Handle field_values if present
            if (isset($validatedData['field_values'])) {
                $validatedData['field_values'] = is_string($validatedData['field_values']) 
                    ? json_decode($validatedData['field_values'], true) 
                    : $validatedData['field_values'];
            }
    
            $property->update($validatedData);
            DB::commit();
    
            return new PropertyResource($property->load('propertyType'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error updating property'], 500);
        }
    }
  public function getProperties()
    {
        $userId = 1;
        $user = User::findOrFail($userId);
        //$properties = $user->properties()->get();
        $properties = Property::where('owner', $userId)->get();
       // return PropertyResource::collection($properties);
        return response()->json($properties);
    }
    //user-> 
    public function destroy($id)
    {
        // Find the property by ID
        $property = Property::findOrFail($id);
        
        // delete the image from storage if needed
        // Storage::disk('public')->delete($property->images);
        
        // Delete the property record from the database
        $property->delete();
        
        // Return a 204 No Content response
        return response()->json(null, 204);
    }

}
