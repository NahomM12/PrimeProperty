<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        return response()->json($regions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'region_name' => 'required|string|unique:regions',
        ]);

        $region = Region::create($request->all());
        return response()->json($region, 201);
    }

    public function show($id)
    {
        $region = Region::findOrFail($id);
        return response()->json($region);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'region_name' => 'sometimes|required|string|unique:regions,region_name,' . $id,
        ]);

        $region = Region::findOrFail($id);
        $region->update($request->all());
        return response()->json($region);
    }

    public function destroy($id)
    {
        $region = Region::findOrFail($id);
        $region->delete();
        return response()->json(null, 204);
    }
}
