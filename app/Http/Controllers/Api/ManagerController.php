<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index()
    {
        $managers = Manager::all();
        return response()->json(['managers' => $managers]);
    }

    public function store(Request $request)
    {
        $request->validate([
           // 'name' => 'required|string',
           // 'email' => 'required|email|unique:managers',
            'phone' => 'required|string',
           // 'address' => 'required|string',
            //'status' => 'required|string|in:active,inactive',
        ]);

        $manager = Manager::create([
           // 'name' => $request->name,
           // 'email' => $request->email,
            'phone' => $request->phone,
            //'address' => $request->address,
            //'status' => $request->status,
        ]);

        return response()->json(['message' => 'Manager created successfully', 'manager' => $manager], 201);
    }

    public function show($id)
    {
        $manager = Manager::find($id);
        if (!$manager) {
            return response()->json(['message' => 'Manager not found'], 404);
        }
        return response()->json(['manager' => $manager]);
    }

    public function update(Request $request, $id)
    {
        $manager = Manager::find($id);
        if (!$manager) {
            return response()->json(['message' => 'Manager not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:managers,email,' . $id,
            'phone' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'status' => 'sometimes|required|string|in:active,inactive',
        ]);

        $manager->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Manager updated successfully', 'manager' => $manager]);
    }

    public function destroy($id)
    {
        $manager = Manager::find($id);
        if (!$manager) {
            return response()->json(['message' => 'Manager not found'], 404);
        }
        $manager->delete();
        return response()->json(['message' => 'Manager deleted successfully']);
    }
}
