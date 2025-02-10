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
use App\Http\Controllers\Api\ManagerController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\SubRegionController;
use App\Http\Controllers\Api\LocationController;
         

Route::middleware(['auth:sanctum'])->group(function () {
    // Routes for both sellers and customers
    Route::get('/get-properties', [PropertyController::class, 'getProperties']);
    Route::apiResource('transactions', TransactionController::class);
    Route::put('/update-wishlist', [AuthController::class, 'updateWishlist']);
    Route::get('get-transactions', [TransactionController::class,'getTransactions']);
    //address routes
    Route::apiResource('regions', RegionController::class);
    Route::apiResource('subregions', SubRegionController::class);
    Route::apiResource('locations', LocationController::class);
    
    // get Properties Routes
    Route::get('/properties/{use}', [PropertyController::class, 'getPropertiesByUse']);
    Route::get('/properties-for-rent', [PropertyController::class, 'getPropertiesForRent']);
    Route::get('/property/{id}', [PropertyController::class, 'ShowProperty']);
    Route::put('/change-language', [AuthController::class, 'changeLanguage']);
    Route::put('/change-language', [AuthController::class, 'changeLanguage']);
    Route::apiResource('customers', CustomerController::class);
    //buy & rent routes
    Route::post('/properties/{propertyId}/buy', [CustomerController::class, 'buyProperty']);
    Route::post('/properties/{propertyId}/rent', [CustomerController::class, 'rentProperty']);
    Route::apiResource('managers', ManagerController::class);
    Route::apiResource('property-types', PropertyTypeController::class);
    // Property Fields
    Route::apiResource('property-fields', PropertyFieldController::class);
    Route::post('/request-seller', [AuthController::class, 'requestSeller']);
    Route::post('/update-preference', [AuthController::class, 'updatePreference']);
    Route::apiResource('properties', PropertyController::class);
    Route::apiResource('property-details', PropertyDetailController::class);
    Route::get('/get-propertiesbyregion', [PropertyController::class, 'GetPropertiesbyRegion']);


});



Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/rent-transactions', [TransactionController::class, 'getRentTransactionsByManager']);

});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/profile', [AuthController::class, 'profile']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/adminlogin', [AdminController::class, 'adminLogin']);

Route::post('/manager/login', [AuthController::class, 'managerLogin']);








Route::prefix('viewing-requests')->group(function () {
    Route::post('/', [ViewingRequestController::class, 'requestViewing']);
    Route::post('/{viewingRequest}/suggest-times', [ViewingRequestController::class, 'suggestTimeSlots']);
    Route::post('/{viewingRequest}/select-time', [ViewingRequestController::class, 'selectTimeSlot']);
    Route::get('/pending', [ViewingRequestController::class, 'getPendingRequests']);
    Route::get('/customer/{customerId}', [ViewingRequestController::class, 'getCustomerRequests']);
});
    Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
   
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
    
        Route::middleware('auth:sanctum')->group(function () {
            Route::put('/verify-seller', [AuthController::class, 'verifySeller']); 
        });
    
// Properties




// Property Details

//count views
//Route::get('/count-views/{id}', [PropertyController::class, 'countViews']);
Route::get('/properties/{id}/views', [PropertyController::class, 'countViews']);

Route::get('managers/transactions/sale', [TransactionController::class, 'getSaleTransactionsByManager']);
Route::get('managers/transactions/rent', [TransactionController::class, 'getRentTransactionsByManager']);

Route::get('/user/property-stats', [PropertyController::class, 'getUserPropertyStats']);
// routes/api.php

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/property-stats', [YourController::class, 'getUserPropertyStats'])->name('user.property-stats');
    Route::post('/properties/{id}/bookmark', [YourController::class, 'bookmark'])->name('properties.bookmark');
    Route::get('/properties/{id}/views', [YourController::class, 'countViews'])->name('properties.views');
});
Route::middleware('auth:sanctum')->group(function () {
   
});

Route::get('/properties/manager', [PropertyController::class, 'getPropertiesByManagerRegion']);

// Optional: Add a route to get fields by property type
Route::get('property-types/{id}/form-fields', [PropertyTypeController::class, 'getFormFields']);

// Optional: Add a route for property search
Route::get('properties/search', [PropertyController::class, 'search']);
    
    Route::middleware('auth:sanctum')->group(function () {
       
       
    });
   
    Route::get('/get-wishlist', [AuthController::class, 'getWishlist']);
  // Route::get('/properties/{id}', [PropertyController::class, 'show']);
    Route::apiResource('properties', PropertyController::class);
    Route::apiResource('property-types', PropertyTypeController::class);
    Route::get('/featured', [PropertyController::class, 'getallfeatured']);
    
    //get statistics
         Route::get('/statistics/properties', [AdminController::class, 'getTotalProperties']);
         Route::get('/statistics/users', [AdminController::class, 'getTotalUsers']);
         Route::get('/statistics/revenue', [AdminController::class, 'getTotalRevenue']);
 });