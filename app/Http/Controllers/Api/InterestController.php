<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Interest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;;

class InterestController extends Controller
{
    /**
 * Store user interests.
 *
 * Creates interests for the authenticated user based on provided category IDs.
 * @group user
 * @authenticated
 *
 * @bodyParam id array required Array of category IDs. Example: [11, 12]
 *
 * @response {
 *     "status": true,
 *     "message": "Successfully created interests",
 *     "data": {
 *         "user_id": 1,
 *         "category_id": 13,
 *         "id": 3
 *     }
 * }
 *
 * @response 401 {
 *     "error": "Unauthenticated."
 * }
 *
 * @response 422 {
 *     "errors": {
 *         "id": [
 *             "The id field is required."
 *         ]
 *     }
 * }
 *
 * @response 500 {
 *     "success": false,
 *     "message": "Internal Server Error"
 * }
 */
    public function storeUserInterest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|array', // Ensure 'id' is an array
            'id.*' => 'required|distinct', // Validate each id in the array
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        
            foreach ($request->id as $categoryId) {
                $interest = new Interest;
                $interest->user_id = $user->id;
                $interest->category_id = $categoryId; 
                $interest->save();
            }
        
            return response()->json([
                'status' => true,
                'message' => 'Successfully created interests',
                'data' => $interest, // Note: This will only contain the last Interest created
            ], 201);
        } catch (\Exception $e) {
            Log::error("#### InterestController->storeUserInterest #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
