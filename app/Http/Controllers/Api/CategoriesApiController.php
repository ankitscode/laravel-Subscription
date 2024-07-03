<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\CommenController;
use Symfony\Component\HttpFoundation\Response;


class CategoriesApiController extends Controller
{

    /**
 * Retrieve all categories.
 *
 * This endpoint retrieves all categories available in the system.
 *
 * @group Categories
 * @authenticated
 * 
 * BearerToken is needed for authentication
 * 
 * @response 200 {
 *     "message": "Successfully retrieved the data of categories",
 *     "subscriptions": [
 *         {
 *             "id": 10,
 *             "name": "Dairy",
 *             "parent_name": null,
 *             "media_id": null,
 *             "is_active": 1,
 *             "is_menu": 1
 *         },
 *         {
 *             "id": 11,
 *             "name": "Vegetables",
 *             "parent_name": null,
 *             "media_id": null,
 *             "is_active": 1,
 *             "is_menu": 1
 *         },
 *         {
 *             "id": 12,
 *             "name": "Meat",
 *             "parent_name": null,
 *             "media_id": null,
 *             "is_active": 1,
 *             "is_menu": 1
 *         },
 *         {
 *             "id": 13,
 *             "name": "Groceries",
 *             "parent_name": null,
 *             "media_id": null,
 *             "is_active": 1,
 *             "is_menu": 1
 *         }
 *     ]
 * }
 * @response 401 {
 *     "error": "Unauthenticated."
 * }
 * @response 500 {
 *     "success": false,
 *     "message": "Internal Server Error"
 * }
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */

    public function getCategories(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        // dd($user);
        try {
            //code...
            $subscriptions = Category::all();
            return response()->json([
                'message' => 'succcessfully retrived the data of categories',
                'subscriptions' => $subscriptions
            ], 200);
        } catch (\Exception $e) {
            Log::error("#### SubscriptionApiController->getCategories #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
