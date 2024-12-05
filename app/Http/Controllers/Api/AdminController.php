<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Owner;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public function createManager(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:managers',
            'phone' => 'required|string',
           // 'address' => 'required|string',
            //'status' => 'required|string|in:active,inactive',
        ]);

        $manager = Manager::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
           // 'address' => $request->address,
            //'status' => $request->status,
        ]);

        return response()->json(['message' => 'Manager created successfully', 'manager' => $manager], 201);
    }
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }

        $admin = Admin::where('user_id', $user->id)->first();

        if (!$admin) {
            return response()->json([
                'message' => 'You are not an admin',
            ], 403);
        }

        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }
    public function approveSellerRequest(Request $request, $id)
    {
        $owner = Owner::findOrFail($id);
        $owner->status = 'approved';
        $owner->save();

        $user = $owner->user;
        $user->role = 'seller';
        $user->sellertab = true;
        $user->save();

        return response()->json(['message' => 'Seller request approved']);
    }

    public function rejectSellerRequest(Request $request, $id)
    {
        $owner = Owner::findOrFail($id);
        $owner->status = 'rejected';
        $owner->save();

        return response()->json(['message' => 'Seller request rejected']);
    }

    public function listSellerRequests()
    {
        $requests = Owner::where('status', 'pending')->with('user')->get();
        return response()->json($requests);
    }
}