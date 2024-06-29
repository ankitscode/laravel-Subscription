<?php

namespace App\Http\Controllers;

use App\Models\Lockup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LockupController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);

        $validData = validator::make($request->all(), [
            'name'              => 'required',
            'lockup_type_id'    => 'required',
        ]);
        if ($validData->fails()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back()->withErrors($validData)->withInput();
        }

        try {
            Lockup::create([
                'name'              => $request->name,
                'lockup_type_id'    => $request->lockup_type_id,
                'other'             => $request->other,
                'is_active'         => isset($request->is_active) && $request->is_active == 'on' ? true : false,
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

        if (isset($id) && !Lockup::where('id', $id)->exists()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }

        try {
            $lockup                 = Lockup::find($id);
            $lockup->name           = is_null($request->edit_name) ? $lockup->name : $request->edit_name;
            $lockup->other          = is_null($request->edit_other) ? $lockup->other : $request->edit_other;
            $lockup->is_active      = isset($request->edit_is_active) && $request->edit_is_active == 'on' ? true : false;
            $lockup->save();

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

        if (isset($id) && !Lockup::where('id', $id)->exists()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
        try {
            Lockup::where('id', $id)->delete();
            Session::flash('alert-success', __('message.record_deteted'));
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('####### LockupController -> destroy() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }
}
