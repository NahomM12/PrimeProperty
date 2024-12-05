<?php

namespace App\Http\Controllers\Api;

use App\Models\SubRegion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class SubRegionController extends Controller
{
    public function index()
    {
        $subRegions = SubRegion::all();
        return response()->json($subRegions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subregion_name' => 'required|string',
            'region_id' => 'required|exists:regions,id',
        ]);

        $subRegion = SubRegion::create($request->all());
        return response()->json($subRegion, 201);
    }

    public function show($id)
    {
        $subRegion = SubRegion::findOrFail($id);
        return response()->json($subRegion);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subregion_name' => 'sometimes|required|string',
            'region_id' => 'sometimes|required|exists:regions,id',
        ]);

        $subRegion = SubRegion::findOrFail($id);
        $subRegion->update($request->all());
        return response()->json($subRegion);
    }

    public function destroy($id)
    {
        $subRegion = SubRegion::findOrFail($id);
        $subRegion->delete();
        return response()->json(null, 204);
    }
}
