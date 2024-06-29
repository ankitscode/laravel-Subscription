<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\LockupType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderDetailsDownload;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class ReportController extends Controller
{
    public function orderReportList()
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
        $statusList = LockupType::find(6)->lockup;

        return view('admin.report.orderReport', compact('statusList'));
    }

    public function dataTableOrderListReport(Request $request)
    {
      if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);

      $query = Order::with(['orderStatus' => function ($query) {
          $query->select("id", "name");
        },'paymentStatus' => function ($query) {
            $query->select("id", "name");
        },'paymentMethod' => function ($query) {
            $query->select("id", "name");
        },'order_items:id,order_id,product_id,product_type,quantity,price,variant,original_price,profit_percentage,profit,discount_amount'])
        ->withCount('order_items');

      #search_key filter
      if (isset($request->filterSearchKey) && !empty($request->filterSearchKey)) {
        $query->where(function ($query) use ($request) {
          $query->where('id', 'like', '%' . $request->filterSearchKey . '%');
        });
      }
      #Date filter
      if (isset($request->filterDate) && !empty($request->filterDate)) {
        $dateRange = isset($request->filterDate) ? explode(" ",$request->filterDate) : null ;
            $from   = date("Y-m-d H:i:s", strtotime($dateRange[0]));
            $to     = date("Y-m-d H:i:s", strtotime($dateRange[2] . ' +1 day'));
        $query->whereBetween('order_date', array($from, $to));
      }
      #status filter
      if (isset($request->filterStatus) && in_array($request->filterStatus, ['16', '17', '18', '19', '20', '20', '21', '22','23'])) {
        $query->where('order_status', $request->filterStatus);
      }

      $query = $query->orderBy('created_at', 'desc')->get();

      if (isset($query) && !empty($query)) {
          $query = self::orderProductDetails($query);
      }

      return (!empty($query['allOrder'])) ? DataTables::of($query['allOrder'])->make(true) : DataTables::of([])->make(true) ;

    }

    #download
    public function downloadOrderReport(Request $request)
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);

        $validData = validator::make($request->all(), [
            'filter_date'  => 'required',
        ]);

        if ($validData->fails()) {
            Session::flash('alert-error', $validData->getMessageBag()->first());
            return redirect()->back();
        }

        try {
            $dateRange = isset($request->filter_date) ? explode(" ",$request->filter_date) : null ;
            $from   = date("Y-m-d H:i:s", strtotime($dateRange[0]));
            $to     = date("Y-m-d H:i:s", strtotime($dateRange[2] . ' +1 day'));

            $query = Order::with(['orderStatus' => function ($query) {
                $query->select("id", "name");
              },'paymentStatus' => function ($query) {
                  $query->select("id", "name");
              },'paymentMethod' => function ($query) {
                  $query->select("id", "name");
              },'order_items:id,order_id,product_id,product_type,quantity,price,variant,original_price,profit_percentage,profit,discount_amount'])
              ->withCount('order_items')
              ->whereBetween('order_date', array($from, $to));

            #status filter
            if (isset($request->order_status) && $request->order_status !== 'all') {
                $query->where('order_status', $request->order_status);
            }
            $orders = $query->orderBy('order_date', 'asc')->get();

            if (isset($orders) && !empty($orders)) {
                $orders = self::orderProductDetails($orders);
            }
            $totalRevenue                   = Order::sum('order_total');
            $taxTotalRevenue                = Order::sum('tax_amount');
            $discountTotalRevenue           = Order::sum('discount_amount');
            $orders['totalRevenue']         = (float)$totalRevenue;
            $orders['taxTotalRevenue']      = (float)$taxTotalRevenue;
            $orders['discountTotalRevenue'] = (float)$discountTotalRevenue;
            #excel
            if ($request->download_type == 'csv') {
                return Excel::download(new OrderDetailsDownload($orders), 'orders_' . time() . '.csv');
            }
        } catch (\Exception $e) {
            Log::error('####### ReportController -> downloadOrderReport() ####### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }


    public static function orderProductDetails($query)
    {
        try {
            $query = collect($query)->map(function($n){
                $product = collect($n->order_items)->map(function($b){
                    $selling_price      = $b->price * $b->quantity;
                    $original_price     = $b->original_price * $b->quantity;
                    $profit             = ($b->profit - $b->discount_amount) * $b->quantity;
                    $profit_percentage  = $b->profit_percentage;

                    return ['selling_price'=>$selling_price,
                            'original_price'=>$original_price,
                            'profit'=>$profit,
                            'profit_percentage'=>$profit_percentage
                        ];
                })->toArray();
                $n['selling_price'] = array_sum(collect($product)->pluck('selling_price')->toArray());
                $n['original_price'] = array_sum(collect($product)->pluck('original_price')->toArray());
                $n['profit'] = array_sum(collect($product)->pluck('profit')->toArray());
                $n['profit_percentage'] = array_sum(collect($product)->pluck('profit_percentage')->toArray());
                return $n;
            });
            // $data['allOrder']                      = $query->toArray();
            $data['allOrder']                      = $query;
            $data['allOrderTotal']                 = $query->sum('order_total');
            $data['allOrderTotalOrignialprice']    = $query->sum('original_price');
            $data['allOrderTotalPrice']            = $query->sum('selling_price');
            $data['allOrderTotalProfit']           = $query->sum('profit');
            $profit_percentage_array               = $query->pluck('profit_percentage')->whereNotNull()->toArray();
            $data['allOrderTotalProfitPercentage'] = ((isset($profit_percentage_array) && !empty($profit_percentage_array)) && $profit_percentage_array != '[]') ? array_sum($profit_percentage_array)/count($profit_percentage_array) : 0 ;
            return $data;
        } catch (\Exception $e) {
            Log::error('####### PackageController -> orderProductDetails #########'. $e->getMessage());
            return null;
        }

    }
}
