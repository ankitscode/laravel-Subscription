<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Models\StoreTimeSchedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ConfigurationController extends Controller
{
    public function index()
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
        $currencyDetail = Configuration::where('name', 'currency')->first();
        $selectedPayemntMethods = Configuration::where('name', 'paymentMethod')->first();
        // $storeTimeSchedule = StoreTimeSchedule::get();
        $store_timing = Configuration::where('name', 'store_timing')->first();
        $googleMapApiKey = Configuration::where('name', 'google_map_api_key')->first();
        $productLocationRadius = Configuration::where('name', 'product_location_radius')->first();
        return view('admin.stores.setting.configuration.configuration',compact('currencyDetail','store_timing','selectedPayemntMethods','googleMapApiKey','productLocationRadius'));
    }

    public function currencyStore(Request $request)
    {
      try {
        $currency = Configuration::where('name', 'currency')->first();
        $currency->data = isset($request->data) ? json_encode($request->data) : null;
        $currency->save();

        Session::flash('alert-success', __('message.data_updated_successfully'));

        return redirect()->back()->withFragment('#store-time');
      } catch (\Exception $e) {
        Log::error('####### ConfigurationController -> currencyStore() #######  ' . $e->getMessage());
        Session::flash('alert-error', __('message.something_went_wrong'));

        return redirect()->back()->withInput();
      }
    }

    public function storeStoreTimeSchedule(Request $request)
    {
        $validData = validator::make($request->all(), [
            'opening_time'  => 'required',
            'closing_time'  => 'required',

        ]);

        if ($validData->fails()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back()->withErrors($validData)->withInput();
        }

        try {
            // StoreTimeSchedule::truncate();
            // for ($i = 0; $i < 7; $i++) {
            //     if ($request->is_active[$i] == 1 && !empty($request->opening_time[$i])) {
            //         $day_type       = $request->day_type[$i];
            //         $opening_time   = $request->opening_time[$i];
            //         $closing_time   = $request->closing_time[$i];
            //         $is_active      = $request->is_active[$i];
            //     } else {
            //         $day_type       = $request->day_type[$i];
            //         $opening_time   = null;
            //         $closing_time   = null;
            //         $is_active      = 0;
            //     }

            //     StoreTimeSchedule::create([
            //         'day_type'      => $day_type,
            //         'opening_time'  => $opening_time,
            //         'closing_time'  => $closing_time,
            //         'is_active'     => $is_active,
            //     ]);
            // }
            $data['opening_time'] = $request->opening_time;
            $data['closing_time'] = $request->closing_time;
            $store_timing = Configuration::where('name', 'store_timing')->first();
            $store_timing->data = isset($data) ? json_encode($data) : null;
            $store_timing->save();

            Session::flash('alert-success', __('message.store_time_schedule_updated_successfully'));
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('####### ConfigurationController -> storeStoreTimeSchedule() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back()->withInput();
        }
    }

    public function paymentMethodStore(Request $request)
    {
      try {
        $selectedPaymentMethod = Configuration::where('name', 'paymentMethod')->first();
        $selectedPaymentMethod->data = isset($request->data) ? json_encode($request->data) : null;
        $selectedPaymentMethod->save();

        Session::flash('alert-success', __('message.data_updated_successfully'));

        return redirect()->back();
      } catch (\Exception $e) {
        Log::error('####### ConfigurationController -> paymentMethodsStore() #######  ' . $e->getMessage());
        Session::flash('alert-error', __('message.something_went_wrong'));

        return redirect()->back()->withInput();
      }
    }

    /**
     * Save the google map api key.
     * Help in address autocomplete api to run while creating a product.
     *
     */
    public function storeGoogleMapApiKey(Request $request)
    {

        $validData = validator::make($request->all(), [
            'google_map_api_key'  => 'required',
        ]);

        if ($validData->fails()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back()->withErrors($validData)->withInput();
        }

        $data['api_key'] = $request->google_map_api_key;

      try {
        $googleApiKeyData = Configuration::where('name', 'google_map_api_key')->first();
        $googleApiKeyData->data = isset($data) ? json_encode($data) : null;
        $googleApiKeyData->save();

        Session::flash('alert-success', __('message.data_updated_successfully'));

        return redirect()->back();
      } catch (\Exception $e) {
        Log::error('####### ConfigurationController -> storeGoogleMapApiKey() #######  ' . $e->getMessage());
        Session::flash('alert-error', __('message.something_went_wrong'));

        return redirect()->back()->withInput();
      }
    }

    /**
     * Store the radius,which will help in searching the product on map within
     * the given radius.
     *
     */
    public function storeProductLocationRadius(Request $request)
    {
        $validData = validator::make($request->all(), [
            'location_radius'  => 'required|numeric',
            'radius_unit'  => 'nullable|string',
        ]);

        if ($validData->fails()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back()->withErrors($validData)->withInput();
        }
        $data['radius'] = $request->location_radius;
        $data['unit'] = $request->unit;

      try {
        $locationRadiusData = Configuration::where('name', 'product_location_radius')->first();
        $locationRadiusData->data = isset($data) ? json_encode($data) : null;
        $locationRadiusData->save();

        Session::flash('alert-success', __('message.data_updated_successfully'));

        return redirect()->back();
      } catch (\Exception $e) {
        Log::error('####### ConfigurationController -> storeProductLocationRadius() #######  ' . $e->getMessage());
        Session::flash('alert-error', __('message.something_went_wrong'));

        return redirect()->back()->withInput();
      }
    }
}
