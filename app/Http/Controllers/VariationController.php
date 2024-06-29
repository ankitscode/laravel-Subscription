<?php

namespace App\Http\Controllers;

use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class VariationController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);

        $validData = validator::make($request->all(), [
            'name'              => 'required',
            'variation_type_id'    => 'required',
        ]);
        if ($validData->fails()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back()->withErrors($validData)->withInput();
        }
        try {
            Variation::create([
                'name'                  => $request->name,
                'variation_type_id'     => $request->variation_type_id,
                'other'                 => $request->other,
                'is_active'             => isset($request->is_active) && $request->is_active == 'on' ? true : false,
            ]);

            Session::flash('alert-success', __('message.records_created_successfully'));
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('####### LockupController -> store() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);

        if (isset($id) && !Variation::where('id', $id)->exists()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
        try {
            $variation                 = Variation::find($id);
            $variation->name           = is_null($request->edit_name) ? $variation->name : $request->edit_name;
            $variation->other          = is_null($request->edit_other) ? $variation->other : $request->edit_other;
            $variation->is_active      = isset($request->edit_is_active) && $request->edit_is_active == 'on' ? true : false;
            $variation->save();

            Session::flash('alert-success', __('message.records_updated_successfully'));
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('####### LockupController -> update() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);

        if (isset($id) && !Variation::where('id', $id)->exists()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
        try {
            Variation::where('id', $id)->delete();
            Session::flash('alert-success', __('message.record_deteted'));
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('####### LockupController -> destroy() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }
}
