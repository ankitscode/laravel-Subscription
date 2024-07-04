<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\SubscriptionApiController;
use App\Http\Controllers\Api\CategoriesApiController;
use App\Http\Controllers\Api\OrderApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
/**
 * Login Api
 */
Route::post('/adminlogin', [AdminAuthController::class, 'adminLogin'])->name('admin.login');

//##forgotpasswordApi
Route::post('password/email', [AdminAuthController::class,'sendResetLinkEmail']);
Route::post('password/reset', [AdminAuthController::class,'reset']);

//##Api for admin
Route::group(['middleware' => ['auth:adminapi']], function () {

    Route::get('/getadminprofile', [AdminAuthController::class, 'getAdminProfile']);
    Route::post('/storeadmin', [AdminAuthController::class, 'storeAdmin']);
    Route::post('/updateAdmin/{id}', [AdminAuthController::class, 'updateAdmin']);
    Route::post('/updateadminimage/{id}', [AdminAuthController::class, 'updateImage']);
    Route::post('/logout', [AdminAuthController::class, 'logout']);
    Route::post('/deleteadmin/{id}', [AdminAuthController::class, 'delete']);
    Route::post('/changepassword', [AdminAuthController::class, 'changePassword']);

    //##Api for getting suscription
    Route::get('/getsubscription', [SubscriptionApiController::class, 'getSubscription']);
    Route::post('/updatesubscription/{id}', [SubscriptionApiController::class, 'updateSubscription']);
    Route::post('/destroysubscription/{id}', [SubscriptionApiController::class, 'destroySubscription']);

    //##Api for getting categories
    Route::get('/getcategories', [CategoriesApiController::class, 'getCategories']);
    Route::post('/updatecategories/{id}', [CategoriesApiController::class, 'updateCategory']);
    Route::post('/destroycategory/{id}', [CategoriesApiController::class, 'destroyCategory']);

    //##Api for orders
    Route::post('/order/{id}', [OrderApiController::class, 'store']);
});
