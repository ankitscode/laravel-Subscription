<?php

namespace App\Http\Controllers\Api;

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



class AdminAuthController extends Controller
{
    /**
     * Log in a Admin.
     *@subgroup admin
     * @bodyParam email string required valid email address The email of the admin.
     * @bodyParam password string required min:8 The password of the admin.
     * 
     * @response {
     *     "success": true,
     *     "token": "eyJhbGciOiAiSFMyNTYiLCJraWQiOiAiYWMwMDZmMDYtMGM0ZC00MTQ5LWE5MTYtYjEwYzQ2N2YzZmMwIn0.eyJqdGkiOiAibG9naW4tY29",
     *     "admin": {
     *    "name": "developer",
     *    "email": "example7pm@gmail.com",
     *    "full_name": "developer",
     *    "phone": "9998553040",
     *    "birthdate": null,
     *   "gender_type": 9,
     *    "city_type": null,
     *    "country_type": null,
     *    "address": null,
     *    "is_active": 1,
     *    "uuid": "c4dcce75-5b80-4ee7-af5e-6d7ce455ee5f",
     *   "profile_image": ""
     *      }
     * }
     * 
     * @response 401 {
     *     "success": false,
     *     "message": "Failed to authenticate."
     * }
     * 
     * @response 500 {
     *     "success": false,
     *     "message": "Internal Server Error"
     * }
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminLogin(Request $request)
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                // successfull authentication
                /** @var \App\Models\User $user **/
                $user = Auth::user();
                return response()->json([
                    'success' => true,
                    'token' => $user->createToken('appToken')->accessToken,
                    'Admin' => $user,
                ], 200);
            } else {
                // failure to authenticate
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to authenticate.',
                ], 401);
            }
        } catch (\Exception $e) {
            Log::error("#### AdminAuthController->login #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve the authenticated admin's profile.
     *
     * @group admin Management
     * @authenticated
     * BearerToken is needed for authentication
     * 
     * @response {
     *     "status": true,
     *     "data": {
     *    "name": "developer",
     *    "email": "example7pm@gmail.com",
     *    "full_name": "developer",
     *    "phone": "9998553040",
     *    "birthdate": null,
     *   "gender_type": 9,
     *    "city_type": null,
     *    "country_type": null,
     *    "address": null,
     *    "is_active": 1,
     *    "uuid": "c4dcce75-5b80-4ee7-af5e-6d7ce455ee5f",
     *   "profile_image": ""
     *      }
     * 
     * @response 401 {
     *     "message": "Unauthenticated."
     * }
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdminProfile(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'message' => 'Unauthenticated.'
                ], 401);
            }

            return response()->json([
                'status' => true,
                'data' => $user,
                'message' => 'get user profile successfully',
            ]);
        } catch (\Exception $e) {
            Log::error("#### AdminAuthController->profile #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Created the authenticated admin's profile.
     * 
     *@authenticated
     * BearerToken is needed for authentication
     * @group admin Management
     * @bodyParam full_name string required The full name of the admin admin.
     * @bodyParam phone string required The phone number of the admin admin.
     * @bodyParam email string required valid email address The email address of the admin admin. Must be unique.
     * @bodyParam birthdate string optional The birthdate of the admin admin (YYYY-MM-DD format).
     * @bodyParam gender_type string optional The gender type of the admin admin. Allowed values: male, female, other.
     * @bodyParam is_active boolean optional Whether the admin admin is active or not (true/false).
     * @bodyParam password string required The password for the admin admin. Minimum length: 8 characters.
     * @bodyParam role_ids array required Array of role IDs assigned to the admin admin.
     * @bodyParam image file optional The profile image of the admin admin (jpg, jpeg, png, gif formats only).
     * @response {
     *     "status": true,
     *     "message": "Admin Created successfully",
     *     "data": {
     *         "name": "John Doe",
     *         "email": "johndoe@example.com",
     *         "image':17777868.jpg,
     *         "created_at": "2024-06-24T12:34:56Z",
     *         "updated_at": "2024-06-24T12:34:56Z"
     *     }
     * }
     * 
     * @response 401 {
     *     "error": "Unauthenticated."
     * }
     * 
     * @response 422 {
     *     "errors": {
     *          
     *             "invalid data format."
     *         
     *     }
     * }
     * 
     * @response 500 {
     *     "error": "Something went wrong."
     * }
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name'     => 'required|string',
            'phone'         => 'required|string',
            'email'         => 'required|email|unique:users,email',
            'birthdate'     => 'nullable|date',
            'gender_type'   => 'nullable|string|in:male,female,other',
            'is_active'     => 'nullable|boolean',
            'password'      => 'required|string|min:8',
            // 'role_ids'      => 'required|array',
            'role_ids.*'    => 'exists:roles,id',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            $user = User::create([
                'full_name'         => $request->full_name,
                'name'              => str_replace(" ", "", $request->full_name),
                'phone'             => $request->phone,
                'email'             => $request->email,
                'birthdate'         => $request->birthdate,
                'gender_type'       => $request->gender_type,
                'is_active'         => $request->filled('is_active'),
                'password'          => Hash::make($request->password),
                'uuid'              => CommenController::generate_uuid('users'),
            ]);

            // Assign roles to user
            $user->syncRoles($request->role_ids);

            // Save image if provided
            if ($request->hasFile('image')) {
                $path = config('image.profile_image_path_store');
                $user->media_id = CommenController::saveImage($request->file('image'), $path);
            }

            $user->save();
            DB::commit();

            return response()->json(['message' => 'Admin created successfully', 'data' => $user], 201);
        } catch (\Exception $e) {
            Log::error("#### AdminAuthController->storeAdmin #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Updated the authenticated admin's profile.
     *
     * @group admin Management
     * @bodyParam full_name string required The full name of the admin user.
     * @bodyParam phone string required The phone number of the admin user.
     * @bodyParam email string required valid email address The email address of the admin user. Must be unique.Non editable.
     * @bodyParam birthdate string optional The birthdate of the admin user (YYYY-MM-DD format).
     * @bodyParam gender_type string optional The gender type of the admin user. Allowed values: male, female, other.
     * @bodyParam is_active boolean optional Whether the admin user is active or not (true/false).
     * @bodyParam password string required The password for the admin user. Minimum length: 8 characters.
     * @bodyParam role_ids array required Array of role IDs assigned to the admin user.
     * @bodyParam image file optional The profile image of the admin user (jpg, jpeg, png, gif formats only).
     * @response {
     *     "status": true,
     *     "message": "Admin updated successfully",
     *     "data": {
     *         "name": "John Doe",
     *         "email": "johndoe@example.com",
     *         "image':17777868.jpg,
     *        "full_name": "developer",
     *        "phone": "9998553040",
     *       "birthdate": null,
     *       "gender_type": 9,
     *       "city_type": null,
     *       "country_type": null,
     *        "created_at": "2024-06-24T12:34:56Z",
     *        "updated_at": "2024-06-24T12:34:56Z"
     *     }
     * }
     * 
     * @response 401 {
     *     "error": "Unauthenticated."
     * }
     * 
     * @response 422 {
     *     "errors": {
     *          
     *             "invalid data format."
     *         
     *     }
     * }
     * 
     * @response 500 {
     *     "error": "Something went wrong."
     * }
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAdmin(Request $request, $id)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string',
            'phone' => 'required|string',
            'gender_type' => 'required|string',
            'birthdate' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Ensure user has permission to edit admin

        DB::beginTransaction();
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            $adminDetails = User::find($id);
            $adminDetails->full_name = $request->input('full_name');
            $adminDetails->name = str_replace(" ", "", $request->input('full_name'));
            $adminDetails->phone = $request->input('phone');
            $adminDetails->gender_type = $request->input('gender_type');
            $adminDetails->birthdate = $request->input('birthdate');
            $adminDetails->save();

            DB::commit();
            return response()->json(
                [
                    'message' => 'Admin updated successfully',
                    ' data' => $adminDetails,
                ],
                200
            );
        } catch (\Exception $e) {
            Log::error("#### AdminAuthController->updateAdmin #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update admin's profile image.
     *@group admin Management
     * This API endpoint updates the profile image of an admin identified by UUID.
     *
     * @authenticated
     * @bodyParam image file required The image file to upload (JPEG, JPG, PNG only, max 2MB).
     * @response 200 {
     *     "message": "Image updated successfully"
     * }
     * @response 400 {
     *     "error": "Invalid image format or size"
     * }
     * @response 401 {
     *     "error": "Unauthorized"
     * }
     * @response 404 {
     *     "error": "Admin not found"
     * }
     * @response 500 {
     *     "error": "Something went wrong"
     * }
     */
    public function updateImage(Request $request, $id)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:6048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            //check for auth user
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            // Save image
            if ($request->hasFile('image')) {
                $path = config('image.profile_image_path_store');
                $media = CommenController::saveImage($request->file('image'), $path);

                // Update admin's media_id with new image
                $admin = User::where('id', $id)->first();
                if (!$admin) {
                    return response()->json(['error' => 'Admin not found'], 404);
                }
                $admin->media_id = $media;
                $admin->save();
            }
            return response()->json(['message' => 'Image updated successfully',], 200);
        } catch (\Exception $e) {
            Log::error("#### AdminAuthController->UpdateImage #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Change auth  user password.
     * 
     * @group admin Management
     * @authenticated
     * BearerToekn is required for  authentication
     * @bodyParam full_name string required The full name of the admin user.
     * @bodyParam phone string required The phone number of the admin user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            // Check if the old password matches
            if (!Hash::check($request->old_password, Auth::user()->password)) {
                return response()->json([
                    'success' => false,
                    'message' => __('message.old_passward_invalid'),
                ], 400);
            }

            DB::beginTransaction();
            // Update user password
            $user = User::find(Auth::user()->id);
            $user->password = Hash::make($request->password);
            $user->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => __('message.password_updated_successfully'),
            ]);
        } catch (\Exception $e) {
            Log::error("#### AdminAuthController->changePassword #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /** 
     *
     * Log out the authenticated user.
     *
     * @group admin Management
     * @authenticated
     * BearerToekn is required for  authentication
     * @response {
     *  "success": true,
     *  "message": "Logged out successfully.",
     *  "user": {
     *      "name": "developer",
     *      "email": "devsoft7pm@gmail.com",
     *     "full_name": "developer",
     *     "phone": "9998553040",
     *     "birthdate": null,
     *    "gender_type": 9,
     *    "city_type": null,
     *    "country_type": null,
     *    "address": null,
     *    "is_active": 1,
     *    "uuid": "c4dcce75-5b80-4ee7-af5e-6d7ce455ee5f",
     *    "profile_image": "http://127.0.0.1:8000/storage/images/profile/thumbnail_91750701202411001266828c3c31d3f.jpg"
     *  }
     *}
     * 
     * @response 401 {
     *     "success": false,
     *     "message": "Failed to authenticate."
     * }
     * 
     * @response 500 {
     *     "success": false,
     *     "message": "Internal Server Error"
     * }
     * 
     * @return \Illuminate\Http\JsonResponse
     */


    public function logout()
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
                $user->token()->revoke();
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully.',
                    'user' => $user,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to authenticate.',
                ], 401);
            }
        } catch (\Exception $e) {
            Log::error("#### AdminAuthController->logout #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Delete the admin profile.
     *
     * @group admin Management
     * @authenticated
     * BearerToken is required for  authentication
     * @urlParam id required The ID of the user to delete.
     * 
     * 
     * @response {
     *     "status": true,
     *     "message": "Admin deleted successfully",
     *  "user": {
     *      "name": "developer",
     *      "email": "devsoft7pm@gmail.com",
     *     "full_name": "developer",
     *     "phone": "9998553040",
     *     "birthdate": null,
     *    "gender_type": 9,
     *    "city_type": null,
     *    "country_type": null,
     *    "address": null,
     *    "is_active": 1,
     *    "uuid": "c4dcce75-5b80-4ee7-af5e-6d7ce455ee5f",
     *    "profile_image": "http://127.0.0.1:8000/storage/images/profile/thumbnail_91750701202411001266828c3c31d3f.jpg"
     *  }
     * }
     * 
     * @response 401 {
     *     "error": "Unauthenticated."
     * }
     * 
     * @response 500 {
     *     "error": "Something went wrong."
     * }
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        DB::beginTransaction();
        try {
            User::where('id', '=', $id)->delete();
            DB::commit();
            return response()->json(['status' => 'Admin deleted successfully']);
        } catch (\Exception $e) {
            Log::error("#### AdminAuthController->logout #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
