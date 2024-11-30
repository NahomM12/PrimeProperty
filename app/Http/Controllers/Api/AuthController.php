<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use App\Models\Manager;
use App\Models\SellerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }
    

    public function managerLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        if ($user->role !== 'manager') {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function requestSeller(Request $request)
    {
        $request->validate([
            'govt_id_number' => ['required', 'string', 'unique:owners'],
        ]);

        $user = Auth::user();

        if ($user->owner) {
            return response()->json([
                'message' => 'Seller request already submitted'
            ], 400);
        }

        $owner = Owner::create([
            'user_id' => $user->id,
            'govt_id_number' => $request->govt_id_number,
            'status' => 'pending',
            'phone' => $user->phone,
            'address' => $user->address,
        ]);

        return response()->json([
            'message' => 'Seller request submitted successfully',
            'request' => $owner,
        ]);
    }

    public function updatePreference(Request $request)
    {
        $request->validate([
            'preference' => ['required', 'string', 'in:light,dark'],
        ]);

        $user = Auth::user();
        $user->update(['preference' => $request->preference]);

        return response()->json([
            'message' => 'Preference updated successfully',
            'preference' => $user->preference,
        ]);
    }

    public function updateWishlist(Request $request)
    {
        $request->validate([
            'action' => ['required', 'string', 'in:add,remove'],
            'property_id' => ['required', 'integer', 'exists:properties,id'],
        ]);

        $user = Auth::user();
        $wishlist = $user->wishlist ?? [];

        if ($request->action === 'add') {
            if (!in_array($request->property_id, $wishlist)) {
                $wishlist[] = $request->property_id;
            }
        } else {
            $wishlist = array_diff($wishlist, [$request->property_id]);
        }

        $user->update(['wishlist' => $wishlist]);

        return response()->json([
            'message' => 'Wishlist updated successfully',
            'wishlist' => $user->wishlist,
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}