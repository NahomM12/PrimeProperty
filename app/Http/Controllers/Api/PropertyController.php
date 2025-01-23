<?php

namespace App\Http\Controllers\Api;

use App\Models\Property;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Resources\PropertyResource;
use App\Models\PropertyView;
use App\Models\PropertyType;
use App\Models\Manager;
use App\Models\Region;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\DB;
use Cloudinary\Transformation\Transformation;
use Illuminate\Support\Facades\Log;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with('propertyType', 'subregion.region', 'location');
    
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
    
        if ($request->has('region_id')) {
            $query->whereHas('region', function ($q) use ($request) {
                $q->where('id', $request->region_id);
            });
        }
    
        if ($request->has('subregion_id')) {
            $query->whereHas('subregion', function ($q) use ($request) {
                $q->where('id', $request->subregion_id);
            });
        }
    
        if ($request->has('location_id')) {
            $query->whereHas('location', function ($q) use ($request) {
                $q->where('id', $request->location_id);
            });
        }
    
        $properties = $query->get();
        return PropertyResource::collection($properties);
    }
    public function GetPropertiesbyRegion()
    {
        $manager_id = 2;
        Log::debug('passed');
        
        // Find the manager by ID
        $manager = Manager::findOrFail($manager_id);
        Log::debug($manager);
        
        // Get properties that have the same region_id as the manager
        $properties = Property::where('subregion_id', $manager->sub_region_id)->get();
        
        return response()->json($properties);
    }
    
        public function getPropertiesForRent(Request $request)
{
    $query = Property::with('propertyType') 
                     ->where('property_use', 'rent')
                     ->where('status', 'available');

    $properties = $query->get();

    $response = [];
    foreach ($properties as $property) {
        $response[] = [
            'title' => $property->title,
            'owner' => $property->owner,
            'date' => $property->created_at->format('Y-m-d'),
            'price' => $property->price,
        ];
    }

    return response()->json($response);
}

public function store(Request $request)
{
    Log::debug('First log: Request received', $request->all());

    DB::beginTransaction();

    try {
        // Validate the request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'sometimes|string|max:255',
            
            'price' => 'required|numeric|min:0',
            'images' => 'sometimes|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|string|in:available,sold,rented',
            'property_use' => 'required|string|in:rent,sale',
            'property_type_id' => 'sometimes|exists:property_types,id',
            'field_values' => 'array|nullable|sometimes',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'owner' => 'nullable|exists:users,id',
            'region_id' => 'sometimes|exists:regions,id',
            'subregion_id' => 'sometimes|exists:sub_regions,id',
            'location_id' => 'sometimes|exists:locations,id', // Accept location string input
        ]);

       

        // Create the property
        $property = Property::create($validatedData);
        Log::debug('Second log: Property created', ['property_id' => $property->id]);

        // Handle image uploads if any
        if ($request->hasFile('images')) {
            Log::debug('Image upload process started');

            $uploadedImageUrls = [];

            foreach ($request->file('images') as $image) {
                $uploadedFileUrl = cloudinary()->upload($image->getRealPath(), [
                    'folder' => 'properties_images',
                    'transformation' => [
                        'width' => 400,
                        'height' => 400,
                        'crop' => 'fill',
                    ],
                ])->getSecurePath();

                $uploadedImageUrls[] = $uploadedFileUrl;
                Log::debug('Image uploaded and attached to property', ['url' => $uploadedFileUrl]);
            }

            $property->images = $uploadedImageUrls;
        }

        DB::commit();

        return response()->json([
            'message' => 'Property created successfully',
            'data' => new PropertyResource($property->load('propertyType', 'region', 'subregion')),
        ], 201);
    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Error creating property', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'message' => 'Error creating property',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    public function getPropertiesByUse($use)
    {
        Log::debug($use);
        try {
            $properties = Property::where('property_use', $use)->get();
            return response()->json($properties);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    
  /*  public function show($id)
{
    $property = Property::with('propertyType')->findOrFail($id);

    // Check if the current user has already viewed the property
    $hasViewed = $property->views()->where('user_id', auth()->id())->exists();

    if (!$hasViewed) {
        // Create a new property view record
        $property->views()->create([
            'user_id' => auth()->id(),
        ]);
    }

    return new PropertyResource($property);
}*/
public function show($id)
{
    try {
        $property = Property::with([
            'propertyType',
            'region',
            'subregion',
            'location'
        ])->findOrFail($id);

        Log::debug('Property found:', ['property' => $property]);

        // Manual user ID approach
        $userId = 1; // Static user ID as in your example
        $hasViewed = $property->views()->where('user_id', $userId)->exists();
        
        if (!$hasViewed) {
            $property->views()->create([
                'user_id' => $userId,
            ]);
        }

        return new PropertyResource($property);

    } catch (\Exception $e) {
        Log::error('Error fetching property:', [
            'id' => $id,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'message' => 'Property not found',
            'error' => $e->getMessage()
        ], 404);
    }
}

public function ShowProperty($id) 
{
    try {
        $property = Property::with([
            'propertyType',
            'region',
            'subregion',
            'location'
        ])->findOrFail($id);

        Log::debug('Property found:', ['property' => $property]);

        // Manual user ID approach
        $userId = 2; // Static user ID as in your example
        $hasViewed = $property->views()->where('user_id', $userId)->exists();
        
        if (!$hasViewed) {
            $property->views()->create([
                'user_id' => $userId,
            ]);
        }

        return new PropertyResource($property);

    } catch (\Exception $e) {
        Log::error('Error fetching property:', [
            'id' => $id,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'message' => 'Property not found',
            'error' => $e->getMessage()
        ], 404);
    }
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
    /*public function countViews($id)
{
    $property = Property::findOrFail($id);
    $uniqueViews = $property->views()->distinct('user_id')->count();

    return response()->json([
        'property_id' => $property->id,
        'unique_views' => $uniqueViews,
    ]);
}*/
public function countViews($id)
{
    $property = Property::findOrFail($id);
    $property->increment('views_count');

    return response()->json([
        'property_id' => $property->id,
        'unique_views' => $property->views_count,
    ]);
}

public function bookmark(Request $request, $id)
{
    $user = auth()->user();
    $property = Property::findOrFail($id);

    if ($user->wishlist->contains($id)) {
        $user->wishlist->pull($id);
        $property->decrement('bookmarks_count');
    } else {
        $user->wishlist->push($id);
        $property->increment('bookmarks_count');
    }

    $user->save();

    return response()->json([
        'bookmarked' => $user->wishlist->contains($id),
        'total_bookmarks' => $property->bookmarks_count,
    ]);
}

public function getUserPropertyStats(Request $request)
{
   // $user = auth()->user();
   $userid = 31;
    $user = User::findOrFail($userid);
    $properties = $user->properties;

    $data = [
        'total_properties_listed' => $properties->count(),
        'total_views_of_properties' => $properties->sum('views_count'),
        'total_bookmarks_of_properties' => $properties->sum('bookmarks_count'),
    ];

    return response()->json($data);
}
public function getPropertiesByManagerRegion($managerId)
{
    $managerId = 1;
    $manager = Manager::with('region')->findOrFail($managerId);

    return Property::where('region_id', $manager->region_id)
                    //->where('sub_region_id', $manager->sub_region_id)
                    ->get();
                    return response()->json($properties);
}

    public function destroy($id)
    {
        // Find the property by ID
        $property = Property::findOrFail($id);
        
        $property->delete();
        
        // Return a 204 No Content response
        return response()->json(null, 204);
    }

}
