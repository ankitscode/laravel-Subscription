<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Lockup;
use App\Models\LockupType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{

  public function adminOrderIndex()
  {
    if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
    $orderStatus = LockupType::find(6)->lockup;

    return view('admin.sales.order.order_list', compact('orderStatus'));
  }

  public function adminOrderItemDetails($id)
  {
    try {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
        $allOrders = Order::with('orderStatus:id,name','paymentStatus:id,name','order_items','user.media','orderAddress.city','orderAddress.state','orderAddress.country','paymentMethod','paymentStatus','paymentDetails')
                            ->withCount('order_items')
                            ->where('id', $id)
                            ->first();
        $orderStatus = LockupType::find(6)->lockup;
        $paymentStatus = LockupType::find(8)->lockup;
        return view('admin.sales.order.view_order', compact('allOrders', 'orderStatus','paymentStatus'));
    } catch (\Exception $e) {
        Log::error('##### OrderCntroller->adminOrderItemsDetails #####'.$e->getMessage());
        return redirect()->back();
    }
  }

  public function dataTableAdminOrderList(Request $request)
  {
    if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);

    $query = Order::with(['user' => function ($query) {
        $query->select("id", "name", "email");
      }, 'orderStatus' => function ($query) {
        $query->select("id", "name");
      }])
      ->withCount('order_items');
    #search_key filter
    if (isset($request->filterSearchKey) && !empty($request->filterSearchKey)) {
      $query->where(function ($query) use ($request) {
        $query->where('id', 'like', '%' . $request->filterSearchKey . '%');
      });
    }

    #status filter
    if (isset($request->filterStatus) && in_array($request->filterStatus, ['16', '17', '18', '19', '20', '20', '21', '22','23'])) {
      $query->where('order_status', $request->filterStatus);
    }

    $query = $query->orderBy('created_at', 'desc');

    if (!empty($query)) {

      return DataTables::of($query)->make(true);
    }

    return DataTables::of([])->make(true);

  }

  public function changeOrderStatus(Request $request, $id)
  {
    if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);

    if ($request->ajax()) {
      try {
        $orderDetails = Order::findOrFail($id);
        $orderDetails->order_status = $request->selectedOptionValue;
        $orderDetails->save();
        try {
          $user = User::findOrFail($orderDetails->user_id);
          $orderStatus = Lockup::where("id", $orderDetails->order_status)->first();
        //   Notification::send($user, new OrderStatusNotification($orderStatus));
        } catch (\Exception $e) {
          Log::error("############## OrderController -> changeOrderStatus -> OrderStatusNotification ##############" . $e->getMessage());
        }
      } catch (\Exception $e) {
        Log::error("##################### OrderController -> changeOrderStatus ######################" . $e->getMessage());
      }
    }
  }

  public function changePaymentStatus(Request $request)
  {
    if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);

    if ($request->ajax()) {
      try {
        $orderDetails = Order::findOrFail($request->id);
        $orderDetails->payment_status = $request->selectedOptionValue;
        $orderDetails->save();
        // try {
        //   // Notification::send($user, new NewOrderNotification($orderDetailsForMail));
        //   $user = User::findOrFail($orderDetails->user_id);
        //   $orderStatus = Lockup::where("id", $orderDetails->payment_status)->first();
        //   Notification::send($user, new OrderStatusNotification($orderStatus));
        // } catch (\Exception $e) {
        //   Log::error("############## OrderController -> changeOrderStatus -> NewOrderNotification ##############" . $e->getMessage());
        // }
      } catch (\Exception $e) {
        Log::error("##################### OrderController -> changePaymentStatus ######################" . $e->getMessage());
      }
    }
  }

}
