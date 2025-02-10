<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use App\Models\Manager;
use Illuminate\Http\Request;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required',  Password::defaults()],
            'phone' => ['required', 'string', 'max:255'],
            //'address' => ['required', 'string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            //'role' => 'customer',
            'phone' => $request->phone,
            //'address' => $request->address,
            //'sellertab'=>,
            //  'wishlist' => 'property',//add property id to it 
            //'preference' => 'light',
            //'language' => 'Eng',
            //'mode' =>'customer',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
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

        $token = $user->createToken('auth_token', ['role:' . $user->role])->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    
    public function verifySeller(Request $request)
    {
        try {
            $user = auth()->user();
            $user->seller_tab = 'active';
            $user->save();
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function managerLogin(Request $request)
{
    Log::debug($request->all());

    // Validate the request
    $request->validate([
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ]);

    Log::debug("Validation passed");

    // Attempt to authenticate the manager using the 'manager' guard
    if (!auth('manager')->attempt($request->only('email', 'password'))) {
        Log::debug("Invalid login details");
        return response()->json([
            'message' => 'Invalid login details'
        ], 401);
    }

    Log::debug("Manager authenticated");

    // Retrieve the authenticated manager
    $manager = auth('manager')->user();

    // Create a token for the manager
    $token = $manager->createToken('auth_token', ['role:manager'])->plainTextToken;

    Log::debug("Token generated");

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $manager,
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
            'message' => '{mode updated successfully',
            'preference' => $user->preference,
        ]);
    }
    public function changeLanguage(Request $request)
    {
        $request->validate([
            'language' => ['required', 'string', 'in:Eng,Amh'],
        ]);
// todo change the user id
        $userId = 1; 
        $user = User::find($userId);//User::find($userid);
        Log::debug($user);
        $user->update(['language' => $request->language]);
             
        return response()->json([
            'message' => 'Language updated successfully',
            'language' => $user->language,
        ]);
    }
public function changeMode(Request $request)
{
    $request->validate([
        'mode' => ['required', 'string', 'in:light,dark'],
    ]);
    $userId = 1;
    $user = User::find($userId);
     $user->update(['preference'=> $request->mode]);
     return response()->json([
        'message' =>  'mode applied successfully',
        'mode' => $user->preference,
    ]);
}


// public function updateWishlist(Request $request)
// {
//     Log::debug('Entering updateWishlist method', ['request_data' => $request->all()]);

//     $request->validate([
//         'prodId' => ['required', 'integer', 'exists:properties,id'],
//     ]);

//     // Get the authenticated user
//     $user = auth()->user();

//     if (!$user) {
//         Log::error('Unauthorized access attempt to update wishlist.');
//         return response()->json(['message' => 'Unauthorized'], 401);
//     }

//     Log::debug('Authenticated user found', ['user_id' => $user->id]);

//     // Retrieve the wishlist, ensuring it's an array
//     $wishlist = $user->wishlist ?? [];
//     Log::debug('Current wishlist before update', ['wishlist' => $wishlist]);

//     if (!in_array($request->prodId, $wishlist)) {
//         $wishlist[] = $request->prodId;
//         Log::debug('Product added to wishlist', ['prodId' => $request->prodId]);
//     } else {
//         $wishlist = array_diff($wishlist, [$request->prodId]); // Remove the item
//         Log::debug('Product removed from wishlist', ['prodId' => $request->prodId]);
//     }

//     // Update the user's wishlist
//     $user->update(['wishlist' => array_values($wishlist)]);
//     Log::debug('Wishlist updated successfully', ['new_wishlist' => $wishlist]);

//     return response()->json([
//         'message' => 'Wishlist updated successfully',
//         'wishlist' => $user->wishlist,
//     ]);
// }

    public function profile(Request $request)
    {
        $userId = 1;
        $user = User::find($userId);
        return response()->json([
            'user' => $user,
        ]);
    }
    public function getWishlist()
    {
        $userId =1;
        try {
            $user = User::findOrFail($userId);
            $wishlistIds = $user->wishlist; // Get the wishlist array

            // Fetch products that match the IDs in the wishlist
            $properties = Property::whereIn('id', $wishlistIds)->get();
    
            return response()->json([
                'message' => 'Wishlist updated successfully',
                'wishlist' => $properties,
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
/* public function addToWishlist(Request $request)
{
    $request->validate([
        'prodId' => ['required', 'integer', 'exists:products,id'],
    ]);

    $user = auth()->user();
    $wishlist = $user->wishlist ?? [];

    if (!in_array($request->prodId, $wishlist)) {
        $wishlist[] = $request->prodId;
        $user->update(['wishlist' => $wishlist]);
        return response()->json($user);
    } else {
        $wishlist = array_diff($wishlist, [$request->prodId]);
        $user->update(['wishlist' => $wishlist]);
        return response()->json($user);
    }
}
     return response()->json([
            'message' => 'Wishlist updated successfully',
            'wishlist' => $user->wishlist,
        ]);
 */

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}