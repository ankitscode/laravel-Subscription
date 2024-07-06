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
use App\Models\SubscriptionPlan;
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
    /**
     * Update a subscription.
     *
     * This endpoint updates an existing subscription based on the provided subscription ID.
     *
     * @group Subscriptions
     * @authenticated
     * @urlParam id integer required The ID of the subscription to update.
     * @bodyParam name string required The name of the subscription. Example: "halfed Yearly"
     * @bodyParam duration string required The duration of the subscription. Example: "30 days"
     * @bodyParam amount string required The amount of the subscription. Example: "1500"
     *
     * @response {
     *     "message": "subscription updated successfully",
     *     "data": {
     *         "id": 17,
     *         "name": "halfed Yearly",
     *         "duration": "30 days",
     *         "amount": "1500",
     *         "uuid": null,
     *         "remember_token": null,
     *         "is_active": 1
     *     }
     * }
     * @response 400 {
     *     "error": {
     *         "name": [
     *             "The name field is required."
     *         ]
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function updateSubscription(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'duration' => 'required|string',
            'amount' => 'required|string',
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

            $subscription = Subscription::find($id);
            $subscription->name = $request->name;
            $subscription->duration = $request->duration;
            $subscription->amount = $request->amount;
            $subscription->save();

            DB::commit();
            return response()->json(
                [
                    'message' => 'subscription updated successfully',
                    ' data' => $subscription,
                ],
                200
            );
        } catch (\Exception $e) {
            Log::error("#### SubscriptionApiController->updateSubscription #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Destroy a subscription.
     *
     * This endpoint deletes a subscription associated with the authenticated user.
     *
     * @group Subscriptions
     * @authenticated
     * @urlParam id integer required The ID of the subscription to be deleted.
     * @response {
     *     "status": "Subscription deleted successfully"
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
    public function destroySubscription(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        DB::beginTransaction();
        try {
            Subscription::where('id', '=', $id)->delete();
            DB::commit();
            return response()->json(['status' => 'Subscription deleted successfully']);
        } catch (\Exception $e) {
            Log::error("#### AdminAuthController->logout #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
/**
 * Store a new subscription plan for the authenticated user.
 *
 * This endpoint allows authenticated users to activate a subscription plan.
 *
 * @group user
 * @authenticated
 *
 * @bodyParam id integer required The ID of the subscription to activate.
 *
 * @response {
 *     "status": true,
 *     "message": "Successfully activated subscription plan",
 *     "data": {
 *         "user_id": 1,
 *         "subscription_id": 18,
 *         "id": 2
 *     }
 * }
 * @response 401 {
 *     "error": "Unauthenticated."
 * }
 * @response 422 {
 *     "errors": {
 *         "id": [
 *             "The id field is required."
 *         ]
 *     }
 * }
 * @response 500 {
 *     "success": false,
 *     "message": "Internal Server Error"
 * }
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
    public function storeUserSubscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            //code...
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            $subscription = new SubscriptionPlan();
            $subscription->user_id = $user->id;
            $subscription->subscription_id = $request->id;
            $subscription->save();
            return response()->json([
                'status' => true,
                'message' => 'succsfully activated subscriptionplan',
                'data' => $subscription
            ], 201);
        } catch (\Exception $e) {
            Log::error("#### InterestController->storeUserInterest                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
