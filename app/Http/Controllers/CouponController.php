<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Lockup;
use App\Models\Product;
use App\Models\Category;
use App\Models\UserType;
use App\Models\LockupType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\frontend\Cart;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreCouponRequest;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
  public function couponIndex()
  {
  if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
    return view('admin.marketing.promotion.coupon');
  }

  public function createCoupon()
  {
    if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
    $couponRules = Lockup::getLockupByKey('couponRule');
    $categories = Category::get();
    $userTypes = UserType::all();
    return view('admin.marketing.promotion.create_coupon',compact('couponRules','categories','userTypes'));
  }

  /**
   * Return the details view of a coupon
   *
   * @param $id coupon id is required
   */
  public function viewCoupon($id)
  {
    $validator = Validator::make(['id' => $id], [
        'id' => ['required', 'exists:coupons,id'],
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
    try {

        $couponDetails = Coupon::findOrFail($id);
        $couponRules = LockupType::findOrFail(12)->lockup;
        $categories = Category::get();
        $userTypes = UserType::all();
        return view('admin.marketing.promotion.view_coupon',compact('couponDetails','categories','userTypes','couponRules'));

    } catch (\Exception $e) {
        Log::error('####### CouponController -> viewCoupon() ####### ' . $e->getMessage());
        Session::flash('alert-error', __('message.something_went_wrong'));
        return redirect()->back();
    }
  }

  public function storeCoupon(StoreCouponRequest $request)
  {
    if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
    try {
      Coupon::create([
        'coupon_option' => $request->coupon_option,
        'coupon_code' => isset($request->coupon_code)?$request->coupon_code:Str::random(8),
        'coupon_rule' => $request->coupon_rule,
        'coupon_rule_data' => $request->has('range')?json_encode($request->range):json_encode($request->coupon_rule_data),
        'uses_per_customer' => $request->uses_per_customer,
        'amount_type' => $request->amount_type,
        'amount' => isset($request->amount)?$request->amount:0,
        'expiry_date' => $request->expiry_date,
        'status' => isset($request->status) ? $request->status  :   0,
      ]);
      Session::flash('alert-success', __('message.Coupon_saved_successfully'));
      return redirect()->route('admin.couponList');
    } catch (\Exception $e) {
      Log::error("################ CouponController -> storeCoupon #######################" . $e->getMessage());
      Session::flash('alert-error',__('message.something_went_wrong'));
      return redirect()->back()->withInput();
    }


  }

  public function editCoupon($id)
  {
    if(!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
    $couponDetails = Coupon::findOrFail($id);
    $couponRules = LockupType::findOrFail(12)->lockup;
    $categories = Category::get();
    $userTypes = UserType::all();
    return view('admin.marketing.promotion.edit_coupon',compact('couponDetails','categories','userTypes','couponRules'));
  }

  public function updateCoupon(Request $request, $id)
  {
    if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
    try {
      $coupon = Coupon::findOrFail($id);
      $coupon->uses_per_customer = $request->uses_per_customer;
      $coupon->amount_type = $request->amount_type;
      $coupon->amount = isset($request->amount)?$request->amount:0;
      $coupon->coupon_rule_data = $request->has('range')?json_encode($request->range):json_encode($request->coupon_rule_data);
      $coupon->expiry_date = $request->expiry_date;
      $coupon->status = $request->has('status');
      $coupon->save();

      Session::flash('alert-success', __('message.Coupon_updated_successfully'));
        return redirect()->route('admin.couponList');
    } catch (\Exception $e) {
      Log::error("################# CouponController -> updateCoupon #####################" .$e->getMessage());
      Session::flash('alert-error',__('message.something_went_wrong'));
      return redirect()->back()->withInput();
    }
  }

  public function destroyCoupon($id)
  {
    if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
    try {
    Coupon::where('id', $id)->delete();
    return response()->json(['status' => 'Coupon deleted successfully']);
    } catch (\Exception $e) {
    Log::error('####### CouponController -> destroyCoupon() ####### ' . $e->getMessage());
    Session::flash('alert-error', __('message.something_went_wrong'));
    return redirect()->back();
    }
  }

//   public function applyCoupon(Request $request)
//   {
//     if ($request->ajax()) {

//       $data = $request->all();
//       $couponCount = Coupon::where('coupon_code', $data['code'])->count();

//       if ($couponCount == 0) {
//         return response()->json(['status' => false, 'message' => 'This coupon is not valid', 'data' => $data]);
//       } else {
//         $result = self::verifyCoupon($data['code']);
//         return response()->json($result);
//       }
//     }
//   }

//   public function removeCoupon(Request $request)
//   {
//     if ($request->ajax())
//     {
//       try {
//         if (session()->has('discount')){
//         session()->forget('discount');
//         }
//         return response()->json(['status'=> true,'message'=>"Coupon removed"]);

//       } catch (\Exception $e) {

//         Log::error('###### CouponController -> removeCoupon() #####' .$e->getMessage());

//         return response()->json(['status'=> false,'message'=>"Somthing Went Wrong"]);
//       }

//     }
//   }

  public function dataTableAllCoupon()
  {
    if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
    $query = Coupon::with('lockup')->get();
    if (!empty($query)) {
      return DataTables::of($query)->make(true);
    }
    return DataTables::of([])->make(true);
  }

//   // ===========================================

//   public static function verifyCoupon($data)
//   {
//     $couponDetails = Coupon::where('coupon_code', $data)->first();
//     $expiryDate = $couponDetails->expiry_date;
//     $currentDate = date('Y-m-d');
//     $couponRule = $couponDetails->coupon_rule;
//     $cartItems = Cart::cartItems();                              # Items in cart
//     $cartItemsCategories = collect($cartItems)->map(function ($n) {
//       $categoryDetails = collect($n['product']['categories'])->map(function ($j) {
//         return $j['category_id'];
//       });
//       return $categoryDetails;
//     })->collapse()->toArray();

//     if ($couponDetails->status != 1)
//     {
//       $message = "This coupon is not active";
//     }
//     elseif ($currentDate > $expiryDate)
//     {
//       $message = "This coupon is expired";
//     }
//     elseif ($couponDetails->status == 1 && $currentDate < $expiryDate){
//       switch ($couponRule) {
//         case '9':

//           $couponRuleData = collect($couponDetails->coupon_rule_data)->map(function($n){
//             return intval($n);
//           })->toArray();

//           $checkCategoryExist =  array_intersect($couponRuleData,$cartItemsCategories); //check category
//           if ($checkCategoryExist == null){
//             $message = 'This Coupon code is not for any of the selected products';
//           }
//           break;


//         case '10':
//           $total = 0;
//           foreach ($cartItems as $items) {
//             $total += $items['price'] * $items['quantity'];
//           };
//           foreach ($couponDetails->coupon_rule_data as $value) {
//             if ($value[0] <= $total && $value[1] >= $total) //check price range
//             {
//                 $discount = $value[2];
//             }
//           };
//           if (!isset($discount)){
//             $message = 'Cart Price is not eligible for discount';
//           }else{
//             $discount;
//           }
//           break;


//         case '11':
//           $couponRuleData = collect($couponDetails->coupon_rule_data)->map(function($n){
//             return intval($n);
//           })->toArray();

//           if (!in_array(Auth::user()->user_type, $couponRuleData))  //check userType
//           {
//             $message = 'You are not Authorize to avail this coupon';
//           }
//           break;

//         default:
//           $message = "No item found";
//           break;
//       }
//     }
//     if (isset($message)) {

//       return [
//               'status' => true,
//               'message' => $message,
//               'couponDetails' => ['coupon_code'=>$data,'amount'=>0,'amount_type'=>'fixed'],
//               'discountAmount' => isset($discount)? $discount : 0 ,
//               'error'=>'Not applicable',
//             ];

//     }else{
//       $couponDetails = Coupon::where('coupon_code', $data)
//                         ->select('coupon_code','amount_type','amount')
//                         ->first();
//       $discountAmount = isset($discount)?
//                          $discount :
//                          0 ;
//       $discountDetails = collect($couponDetails)
//                         ->put('discountAmount',$discountAmount)
//                         ->toArray();

//       $order = session()->get('order');
//       $order['total_amount'] = ($order['items_price'] + $order['tax_value']) - floatval($discountAmount);
//       $order['discount_amount'] = $discountAmount;
//       session()->put('order', $order);

//       session()->put('discount', $discountDetails);

//       return [
//               'status' => true,
//               'message' => "Coupon Remeed Successfully",
//               'couponDetails' => $couponDetails,
//               'discountAmount' => isset($discount)? $discount : 0 ,
//               ];

//     }
//   }

}
