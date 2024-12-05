<?php

namespace App\Http\Controllers\Api;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::all();
        return response()->json($locations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'region_id' => 'required|exists:regions,id',
            'subregion_id' => 'required|exists:sub_regions,id',
            'location' => 'required|string',
        ]);

        $location = Location::create($request->all());
        return response()->json($location, 201);
    }

    public function show($id)
    {
        $location = Location::findOrFail($id);
        return response()->json($location);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'region_id' => 'sometimes|required|exists:regions,id',
            'subregion_id' => 'sometimes|required|exists:sub_regions,id',
            'location' => 'sometimes|required|string',
        ]);

        $location = Location::findOrFail($id);
        $location->update($request->all());
        return response()->json($location);
    }

    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();
        return response()->json(null, 204);
    }
}
