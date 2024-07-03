<?php

namespace App\Http\Controllers;

use App\Models\subscription;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function subscriptionList()
    {
        if(!auth_permission_check('View All Admin')) return redirect()->back();
        try {
            //code...
            return view('admin.subscription.all_subscription_list');
        } catch (\Exception $e) {
            Log::error('####### SubscriptionController -> subscriptionList() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(!auth_permission_check('Create Admin')) return redirect()->back();
        try {
            //code...
            return  view('admin.subscription.create_subscription');
        } catch (\Exception $e) {
            Log::error('####### SubscriptionController -> create() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {    
        if(!auth_permission_check('Create Admin'))return redirect()->back();
        
        try {
            $request->validate([
               'name'=>'required|string|max:30',
               'duration'=>'required',
               'amount'=>'required',
            //    'is_active'=>'required',
            ]);
             
            $subscription= new Subscription;
            $subscription->name=$request->name;
            $subscription->duration=$request->duration;
            $subscription->amount=$request->amount;
            // $subscription->is_active=$request->is_active;
            $subscription->save();
            Session::flash('alert-success', __('message.Subscription_added_successfully'));
            return redirect()->route('admin.subscription');
        } catch (\Exception $e) {
            Log::error('####### SubscriptionController -> store() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function view(string $id)
    {
        if(!auth_permission_check('View All Admin'))abort(404);
        try {
            //code...
            $subscription=Subscription::find($id);
            return view('admin.subscription.view_subscription',compact('subscription'));
        } catch (\Exception $e) {
            Log::error('####### SubscriptionController -> view() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back(); 
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if(auth_permission_check('Edit Admin'));
        try {
            //code...
            $subscription=Subscription::find($id);
            return response()->json(['data' => $subscription]);
            // return view('admin.subscription.Edit_subscription',compact('subscription'));
        } catch (\Exception $e) {
            Log::error('####### SubscriptionController -> edit() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(!auth_permission_check('Edit Admin'))return redirect()->back();
        try {
            //code...
            $request->validate([
                'name'=>'required|string|max:30',
                'duration'=>'required',
                'amount'=>'required',
             ]);              
             $subscription= Subscription::find($id);
             $subscription->name=$request->name;
             $subscription->duration=$request->duration;
             $subscription->amount=$request->amount;
             $subscription->save();
             Session::flash('alert-success', __('message.image_updated_successfully'));
             return redirect()->route('admin.subscription');
        } catch (\Exception $e) {
            Log::error('####### SubscriptionController -> update() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(!auth_permission_check('Delete Admin'))return redirect()->back();
        
        DB::beginTransaction();
        try {
            Subscription::where('id', $id)->delete();
            DB::commit();
            return response()->json(['status' => 'subscription deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('####### SubscriptionController -> destroy() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function subscriptiondatatable()
    {
        return Datatables::of(Subscription::query())
            ->addColumn('Action', function ($subscription) {
                $link = '<a href="' . route('admin.subscriptionEdit', $subscription->id) . '" class="ri-pencil-fill fs-16 btn-sm"></a> ' .
                    '<a href="' . route('admin.subscriptionView', $subscription->id) . '" class="ri-eye-fill fs-16 btn-sm"></a> ' .
                    '<a href="javascript:void(0)" onclick="deleteSubscription(' . $subscription->id . ')" class="ri-delete-bin-5-fill fs-16 text-danger"></a>';
                return $link;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }
}
