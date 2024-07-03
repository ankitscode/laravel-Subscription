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
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        try {
            //code...
            $subscription = Subscription::findOrFail($request->id);
    
            // Generate unique order_id
            $order_id = (string) Str::uuid();

            // Create the order
            $order = new Order();
            $order->order_id = $order_id;
            $order->subscription_id = $subscription->id;
            $order->save();
            return response()->json([
                'status'=>true,
                'message'=>'successfully created orderId',
                'data'=> $order],201);
        } catch (Exception $e) {
            Log::error("#### OrderApiController->store #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
            
        }
        // Fetch the subscription
    }
}

