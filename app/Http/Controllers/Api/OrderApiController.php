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
use Illuminate\Support\Str;
use App\Models\Order;
use Exception;

class OrderApiController extends Controller
{

    /**
     * Store a new order.
     * 
     * This endpoint allows authenticated users to genrate unique id for subscription.
     *
     * @group Orders
     * @authenticated
     * 
     * @bodyParam id integer required The ID of the subscription to associate with the order.
     * 
     * @response {
     *   "status": true,
     *   "message": "successfully created orderId",
     *  "data": {
     *       "order_id": "20e0f8a1-dceb-4c0b-8733-ea66834669c3",
     *      "subscription_id": 18,
     *       "id": 3
     *   }
     *
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            // Generate unique order_id
            $order_id = (string) Str::uuid();
            // Create the order
            $order = new Order();
            $order->order_id = $order_id;
            $order->subscription_id = $request->id;
            $order->save();
            return response()->json([
                'status' => true,
                'message' => 'successfully created orderId',
                'data' => $order
            ], 201);
        } catch (Exception $e) {
            Log::error("#### OrderApiController->store #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

/**
 * Retrieve a list of all orders.
 *
 * This endpoint retrieves a list of all orders available in the system.
 *
 * @group Orders
 * @authenticated
 * 
 * @response {
 *     "status": true,
 *     "message": "succsessfully get all orders",
 *     "data": [
 *         {
 *             "id": 1,
 *             "order_id": "4c3db506-35dc-4382-b0e2-3a195fd4a347",
 *             "subscription_id": 16,
 *             "is_active": 1
 *         },
 *         {
 *             "id": 4,
 *             "order_id": "896da7a6-82e6-4b11-8748-fd9ddd73ff38",
 *             "subscription_id": 18,
 *             "is_active": 1
 *         }
 *     ]
 * }
 *
 * @response 401 {
 *     "error": "Unauthenticated."
 * }
 *
 * @response 500 {
 *     "success": false,
 *     "message": "Internal Server Error"
 * }
 *
 * @return \Illuminate\Http\JsonResponse
 */
    public function orderList()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        try {
            $orders = Order::all();
            return response([
                'status' => true,
                'message' => 'succsessfully get all orders',
                'data' => $orders,
            ],200);
        } catch (\Exception $e) {
            Log::error("#### OrderApiController->orderList #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

/**
 * Retrieve details of a specific order.
 *
 * This endpoint retrieves details of a specific order identified by its ID.
 *
 * @group Orders
 * @urlParam id integer required The ID of the order.
 * @authenticated
 *
 * @response {
 *     "status": true,
 *     "message": "sucessfully get order detail",
 *     "data": {
 *         "id": 1,
 *         "order_id": "4c3db506-35dc-4382-b0e2-3a195fd4a347",
 *         "subscription_id": 16,
 *         "is_active": 1
 *     }
 * }
 *
 * @response 401 {
 *     "error": "Unauthenticated."
 * }
 *
 * @response 404 {
 *     "success": false,
 *     "message": "Order not found."
 * }
 *
 * @response 500 {
 *     "success": false,
 *     "message": "Internal Server Error"
 * }
 *
 * @param  int  $id
 * @return \Illuminate\Http\JsonResponse
 */
    public function orderView($id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        try {
            $order = Order::find($id);
            return response([
                'status' => true,
                'message' => 'sucessfully get order detail',
                'data' => $order,
            ]);
        } catch (\Exception $e) {
            Log::error("#### OrderApiController->orderView #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
