<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Owner;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        // Apply middleware for authentication and admin role checking
        $this->middleware('auth:sanctum'); 
    }

    public function getTotalProperties()
    {
        return response()->json(['totalProperties' => Property::count()]);
    }

    public function getTotalUsers()
    {
        return response()->json(['totalUsers' => User::count()]);
    }

    public function getTotalRevenue()
    {
        $totalRevenue = Transaction::where('transaction_type', 'sale')->sum('price') + 
                        Transaction::where('transaction_type', 'rent')->sum('price');
        return response()->json(['totalRevenue' => $totalRevenue]);
    }

    public function createManager(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:admins', // Ensure unique email in admins table
            'phone' => 'required|string',
            'password' => 'required|min:6',
            'role' => 'required|string|in:manager,admin', // Limit roles to valid options
        ]);

        $manager = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Manager created successfully', 'manager' => $manager], 201);
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }

        $user = User::find($admin->user_id); // Fetch the associated user model if needed

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'admin' => $admin,
            'user' => $user,
        ]);
    }

    public function approveSellerRequest(Request $request, $id)
    {
        $owner = Owner::findOrFail($id);
        $owner->status = 'approved';
        $owner->save();

        $user = $owner->user;
        $user->role = 'seller'; // Assign seller role to the user
        $user->sellertab = true;
        $user->save();

        return response()->json(['message' => 'Seller request approved']);
    }

    public function rejectSellerRequest(Request $request, $id)
    {
        $owner = User::findOrFail($id);
        $owner->status = 'rejected';
        $owner->save();

        return response()->json(['message' => 'Seller request rejected']);
    }

    public function listSellerRequests()
    {
        $requests = User::where('status', 'pending')->with('customer')->get();
        return response()->json($requests);
    }
}
