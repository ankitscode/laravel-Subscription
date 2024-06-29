<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Package;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\updateProfileRequest;
use App\Http\Requests\ChangePasswordRequest;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth_permission_check('View Dashboard')) {
            $userCount             = User::count();
            // $productCount       = Product::all()->count();
            // $orderCount         = Order::all()->count();
            // $totalRevenue       = Order::all()->sum('order_total');
            return view('admin.dashboard.dashboard',compact('userCount'));
        } else {
            Auth::logout();
            Session::flash('alert-error', __('message.unauthorized'));
            return redirect()->route('webRoot');
        }
    }

    public function profile()
    {
        if (!auth_permission_check('View Profile')) abort(404);

        try {
            return view('admin.profile.user_profile');
        } catch (\Exception $e) {
            Log::error('####### HomeController -> profile() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function updateProfile(updateProfileRequest $request)
    {
        if (auth_permission_check('Edit Profile')) abort(404);
        DB::beginTransaction();
        try {
            $user               = User::find(Auth::user()->id);
            $user->full_name    = $request->full_name;
            $user->phone        = $request->phone;
            $user->birthdate    = $request->birthdate;
            $user->gender_type  = $request->gender_type;
            $user->city_type    = $request->city_type;
            $user->country_type = $request->country_type;
            $user->address      = $request->address;
            $user->save();

            DB::commit();

            Session::flash('alert-success', __('message.profile_updated_successfully'));
            return redirect()->route('admin.profile');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('####### HomeController -> updateProfile() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back()->withInput();
        }
    }

    public function updateProfileImage(Request $request)
    {
        if (!auth_permission_check('Edit Profile')) abort(404);

        $validData = validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,jpg,png|max:2048',
        ]);
        if ($validData->fails()) {
            Session::flash('alert-error', $validData->getMessageBag()->first());
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            #save image
            if ($request->hasFile('image')) {
                $path  = config('image.profile_image_path_store');
                $media = CommenController::saveImage($request->image, $path);
                User::where('id', Auth::user()->id)->update(['media_id' => $media]);
            }
            DB::commit();
            Session::flash('alert-success', __('message.profile_image_updated_successfully'));
            return redirect()->route('admin.profile');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('####### HomeController -> updateProfileImage() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function changePassword()
    {
        if (!auth_permission_check('Change Password')) abort(404);

        try {
            return view('admin.profile.change_password');
        } catch (\Exception $e) {
            Log::error('####### HomeController -> changePassword() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function changePasswordStore(ChangePasswordRequest $request)
    {
        if (!auth_permission_check('Change Password')) abort(404);

        if (!Hash::check($request->old_password, Auth::user()->password)) {
            Session::flash('alert-error', __('message.old_passward_invalid'));
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            $user               = User::find(Auth::user()->id);
            $user->password     = Hash::make($request->password);
            $user->save();

            DB::commit();
            Session::flash('alert-success', __('message.password_updated_successfully'));
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('####### HomeController -> changePasswordStore() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    // public static function newOrderNotification()
    // {
    //   $notifications = auth()->user()->unreadNotifications;
    //   return $notifications;
    // }

    // public function markNotification(Request $request)
    // {
    //     auth()->user()
    //         ->unreadNotifications
    //         ->when($request->input('id'), function ($query) use ($request) {
    //             return $query->where('id', $request->input('id'));
    //         })
    //         ->markAsRead();

    //     return response()->noContent();
    // }

    public function changeLanguage($lang)
    {
        App::setLocale($lang);
        Session::put("locale",$lang);
        return redirect()->back();
    }
}
