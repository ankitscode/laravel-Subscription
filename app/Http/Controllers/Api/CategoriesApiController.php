<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
/**
 * Update a category by ID.
 *
 * This endpoint updates the name of a category identified by its ID.
 *
 * @group Categories
 * @authenticated
 * @urlParam id integer required The ID of the category to update.
 * @bodyParam name string required The new name for the category.
 * @response {
 *     "message": "category updated successfully",
 *     "data": {
 *         "id": 10,
 *         "name": "icecream",
 *         "parent_name": null,
 *         "media_id": null,
 *         "is_active": 1,
 *         "is_menu": 1
 *     }
 * }
 * @response 400 {
 *     "error": {
 *         "name": ["The name field is required."]
 *     }
 * }
 * @response 401 {
 *     "error": "Unauthenticated."
 * }
 * @response 500 {
 *     "success": false,
 *     "message": "Internal Server Error"
 * }
 *
 * @param \Illuminate\Http\Request $request
 * @param int $id
 * @return \Illuminate\Http\JsonResponse
 */
    public function updateCategory(Request $request ,$id){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        DB::beginTransaction();
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            $category = Category::find($id);
            $category->name = $request->name;
            $category->save();

            DB::commit();
            return response()->json(
                [
                    'message' => 'category updated successfully',
                    ' data' => $category,
                ],
                200
            );
        } catch (\Exception $e) {
            Log::error("#### CategoryApiController->updateSubscription #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
/**
 * Destroy a category by ID.
 *
 * This endpoint deletes a category identified by its ID.
 *
 * @group Categories
 * @authenticated
 * @urlParam id integer required The ID of the category to delete.
 * @response {
 *     "status": "Category deleted successfully"
 * }
 * @response 401 {
 *     "error": "Unauthenticated."
 * }
 * @response 500 {
 *     "success": false,
 *     "message": "Internal Server Error"
 * }
 *
 * @param \Illuminate\Http\Request $request
 * @param int $id
 * @return \Illuminate\Http\JsonResponse
 */
    public function destroyCategory(Request $request,$id){
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        DB::beginTransaction();
        try {
            Category::where('id', '=', $id)->delete();
            DB::commit();
            return response()->json(['status' => 'Category deleted successfully']);
        } catch (\Exception $e) {
            Log::error("#### CategoriesApiController->logout #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    
}
