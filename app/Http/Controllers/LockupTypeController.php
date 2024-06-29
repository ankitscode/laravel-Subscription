<?php

namespace App\Http\Controllers;

use App\Models\LockupType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LockupTypeController extends Controller
{
    public function index()
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
        try {
            // $lockupTypes =   LockupType::orderBy('created_at', 'desc')->get();
            $lockupTypes =   LockupType::get();
            return view('admin.lockupType.index_lockup_type', compact('lockupTypes'));
        } catch (\Exception $e) {
            Log::error('####### LockupTypeController -> index() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function show($id)
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);

        if (isset($id) && !LockupType::where('id', $id)->exists()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
        try {
            $lockupType = LockupType::where('id', $id)->first();
            return view('admin.lockupType.view_lockup_type', compact('lockupType'));
        } catch (\Exception $e) {
            Log::error('####### LockupTypeController -> show() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }
}
