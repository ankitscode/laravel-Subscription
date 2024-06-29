<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\VariationType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class VariationTypeController extends Controller
{
    public function index()
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
        try {
            $variationTypes =   VariationType::get();
            return view('admin.stores.attributes.attribute_set.index_variation_type', compact('variationTypes'));
        } catch (\Exception $e) {
            Log::error('####### LockupTypeController -> index() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function show($id)
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);

        if (isset($id) && !VariationType::where('id', $id)->exists()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
        try {
            $variationTypes = VariationType::where('id', $id)->first();
            return view('admin.stores.attributes.attribute_set.view_variation_type', compact('variationTypes'));
        } catch (\Exception $e) {
            Log::error('####### LockupTypeController -> show() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);

        $validData = validator::make($request->all(), [
            'name'              => 'required',
        ]);
        if ($validData->fails()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back()->withErrors($validData)->withInput();
        }
        try {
            VariationType::create([
                'name'                  => $request->name,
                'key'                   => str_replace(' ', '_', strtolower($request->name)),
                'is_active'             => isset($request->is_active) && $request->is_active == 'on' ? true : false,
                'is_system'             => isset($request->is_system) && $request->is_system == 'on' ? true : false,
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

        if (isset($id) && !VariationType::where('id', $id)->exists()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
        try {
            $variation                 = VariationType::find($id);
            $variation->name           = is_null($request->edit_name) ? $variation->name : $request->edit_name;
            $variation->is_active      = isset($request->edit_is_active) && $request->edit_is_active == 'on' ? true : false;
            $variation->is_system      = isset($request->edit_is_system) && $request->edit_is_system == 'on' ? true : false;
            $variation->save();

            Session::flash('alert-success', __('message.records_updated_successfully'));
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('####### LockupController -> update() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }
}
