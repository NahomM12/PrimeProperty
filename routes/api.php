<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PropertyTypeController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\PropertyDetailController;
use App\Http\Controllers\Api\ViewingRequestController;
use App\Http\Controllers\Api\TransactionController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/adminlogin', [AdminController::class, 'adminLogin']);
Route::apiResource('customers', CustomerController::class);
Route::post('/manager/login', [AuthController::class, 'managerLogin']);
Route::post('/properties/{propertyId}/buy', [CustomerController::class, 'buyProperty']);
Route::post('/properties/{propertyId}/rent', [CustomerController::class, 'rentProperty']);

Route::apiResource('transactions', TransactionController::class);


Route::prefix('viewing-requests')->group(function () {
    Route::post('/', [ViewingRequestController::class, 'requestViewing']);
    Route::post('/{viewingRequest}/suggest-times', [ViewingRequestController::class, 'suggestTimeSlots']);
    Route::post('/{viewingRequest}/select-time', [ViewingRequestController::class, 'selectTimeSlot']);
    Route::get('/pending', [ViewingRequestController::class, 'getPendingRequests']);
    Route::get('/customer/{customerId}', [ViewingRequestController::class, 'getCustomerRequests']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/request-seller', [AuthController::class, 'requestSeller']);
});

 // Configure API middleware
 Route::middleware('api')
 ->group(function () {
     Route::middleware([
         EnsureFrontendRequestsAreStateful::class,
         ThrottleRequests::class.':api',
         SubstituteBindings::class,
     ])->group(function () {
         // Your API routes go here
         
     });
     Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('/admin/approve-seller/{id}', [AdminController::class, 'approveSellerRequest']);
        Route::post('/admin/reject-seller/{id}', [AdminController::class, 'rejectSellerRequest']);
        Route::get('/admin/seller-requests', [AdminController::class, 'listSellerRequests']);
    });
    
      // Property Types
Route::apiResource('property-types', PropertyTypeController::class);

// Property Fields
Route::apiResource('property-fields', PropertyFieldController::class);

// Properties
Route::apiResource('properties', PropertyController::class);


// Property Details
Route::apiResource('property-details', PropertyDetailController::class);

// Optional: Add a route to get fields by property type
Route::get('property-types/{id}/form-fields', [PropertyTypeController::class, 'getFormFields']);

// Optional: Add a route for property search
Route::get('properties/search', [PropertyController::class, 'search']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/request-seller', [AuthController::class, 'requestSeller']);
        Route::post('/update-preference', [AuthController::class, 'updatePreference']);
        Route::post('/update-wishlist', [AuthController::class, 'updateWishlist']);
    });
    


    Route::apiResource('properties', PropertyController::class);
    Route::apiResource('property-types', PropertyTypeController::class);
 });