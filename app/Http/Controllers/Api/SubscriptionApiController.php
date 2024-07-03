<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscription;
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

class SubscriptionApiController extends Controller
{

/**
 * Retrieve the subscriptions for the authenticated user.
 *
 * This endpoint retrieves all subscriptions associated with the authenticated user.
 *
 * @group Subscriptions
 * @authenticated
 * BearerToken is needed for authentication
 * 
 * @response 200 {
 *     "message": "Successfully retrieved the data of subscriptions",
 *     "subscriptions": [
 *         {
 *             "id": 4,
 *             "name": "Basic plan",
 *             "duration": "1 months",
 *             "amount": "299",
 *             "uuid": null,
 *             "remember_token": null,
 *             "is_active": 1
 *         },
 *         {
 *             "id": 5,
 *             "name": "Half yearly plan",
 *             "duration": "6 Months",
 *             "amount": "1499",
 *             "uuid": null,
 *             "remember_token": null,
 *             "is_active": 1
 *         },
 *         {
 *             "id": 7,
 *             "name": "Yearly Plan",
 *             "duration": "1 Year",
 *             "amount": "2999",
 *             "uuid": null,
 *             "remember_token": null,
 *             "is_active": 1
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

    public function getSubscription(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        // dd($user);
        try {
            //code...
            $subscriptions = Subscription::all();
            return response()->json([
                'message' => 'succcessfully retrived the data of subscriptions',
                'subscriptions' => $subscriptions
            ], 200);
        } catch (\Exception $e) {
            Log::error("#### SubscriptionApiController->getSubscription #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
