<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function adminList()
    {
        if (!auth_permission_check('View All Admin')) abort(404);

        try {
            // $adminTypes = UserType::where('level',1)->get();
            return view('admin.users.admin.all_admin_list');
        } catch (\Exception $e) {
            Log::error('####### AdminController -> adminList() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function createAdmin()
    {
        if (!auth_permission_check('Create Admin')) abort(404);

        try {
            $roles = Role::all();
            return view('admin.users.admin.create_admin', compact('roles'));
        } catch (\Exception $e) {
            Log::error('####### AdminController -> createAdmin() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function storeAdmin(Request $request)
    {
        if (!auth_permission_check('Create Admin')) abort(404);

        DB::beginTransaction();
        try {
            $user = User::create([
                'full_name'         => $request->full_name,
                'name'              => str_replace(" ", "", $request->full_name),
                'phone'             => $request->phone,
                'email'             => $request->email,
                'birthdate'         => $request->birthdate,
                'gender_type'       => $request->gender_type,
                'is_active'         => isset($request->is_active) && $request->is_active == 'on' ? true : false,
                'password'          => Hash::make($request->password),
                'uuid'              => CommenController::generate_uuid('users'),
            ]);

            #assign role to user
            $user->syncRoles($request->role_ids);

            #save image
            if ($request->hasFile('image')) {
                $path  = config('image.profile_image_path_store');
                $user->media_id = CommenController::saveImage($request->image, $path);
            }
            $user->save();
            DB::commit();
            Session::flash('alert-success', __('message.records_created_successfully'));
            return redirect()->route('admin.viewAdmin', $user->uuid);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('#### AdminController -> storeUser() #### ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back()->withInput();
        }

    }

    public function viewAdmin($uuid)
    {
        if (!auth_permission_check('View Admin Details')) abort(404);

        if (!User::where('uuid', $uuid)->exists()) {
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }

        try {
            $adminDetails = User::with('media','genderType')->where('uuid',$uuid)->first();
            return view('admin.users.admin.view_admin',compact('adminDetails'));

        } catch (\Exception $e) {
            Log::error('####### AdminController -> viewAdmin() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }

    }

    public function editAdmin($uuid)
    {
        if (!auth_permission_check('Edit Admin')) abort(404);

        if (!User::where('uuid','=',$uuid)->exists()) {
            Session::flash('alert-error', __('message.record_not_found'));
            return redirect()->back();
        }
        try {
            $adminDetails = User::where('uuid',$uuid)->first();
            $roles = Role::all();
            return view('admin.users.admin.edit_admin',compact('adminDetails','roles'));

        } catch (\Exception $e) {
            Log::error('####### ManageUserController -> editAdmin() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function updateAdmin(Request $request, $uuid)
    {
        if (!auth_permission_check('Edit Admin')) abort(404);

        DB::beginTransaction();
        try {

            $adminDetails                 = User::where('uuid',$uuid)->first();
            $adminDetails->full_name      = $request->full_name;
            $adminDetails->name           = str_replace(" ", "", $request->full_name);
            $adminDetails->phone          = $request->phone;
            $adminDetails->gender_type    = $request->gender_type;
            $adminDetails->birthdate      = $request->birthdate;
            $adminDetails->save();

            // if ($adminDetails->id != 1) {
            //     $adminDetails->is_active   = isset($request->is_active) && $request->is_active == 'on' ? true : false;

            //     #assign role to user
            //     $adminDetails->syncRoles($request->role_ids);
            // }

            DB::commit();
            Session::flash('alert-success', __('message.User_updated_successfully'));
            return redirect()->route('admin.adminList');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('####### ManageUserController -> updateAdmin() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back()->withInput();
        }
    }

    public function updateImage(Request $request,$uuid)
    {
        if (!auth_permission_check('Edit Admin')) abort(404);

        $validData = validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,jpg,png|max:2048',
        ]);
        if ($validData->fails()) {
            Session::flash('alert-error', $validData->getMessageBag()->first());
            return redirect()->back();
        }

        try {
            #save image
            if ($request->hasFile('image')) {
                $path  = config('image.profile_image_path_store');
                $media = CommenController::saveImage($request->image, $path);
                User::where('uuid', $uuid)->update(['media_id' => $media]);
            }

            Session::flash('alert-success', __('message.image_updated_successfully'));
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('####### HomeController -> updateProfileImage() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function destroyAdmin($uuid)
    {
        if (!auth_permission_check('Delete Admin')) abort(404);

        DB::beginTransaction();
        try {
            User::where('uuid','=',$uuid)->delete();
            DB::commit();
            return response()->json(['status' => 'User deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('####### ManageUserController -> updateUser() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();

        }
    }

    public function dataTableAdminsListTable(Request $request)
    {
        if (!auth_permission_check('View All Admin')) return Datatables::of([])->make(true);

        #main query
        $query  = User::with('roles:id,name');

        #search_key filter
        if (isset($request->filterSearchKey) && !empty($request->filterSearchKey)) {
            $query->where(function ($query) use ($request) {
                $query->where('full_name', 'like', '%' . $request->filterSearchKey . '%')
                    ->orWhere('email', 'like', '%' . $request->filterSearchKey . '%')
                    ->orWhere('phone', 'like', '%' . $request->filterSearchKey . '%');
            });
        }

        // #user type filter
        // if (isset($request->filterUserType) && !empty($request->filterUserType) && $request->filterUserType != 'all') {
        //     $query->where('user_type', $request->filterUserType);
        // }

        #status filter
        if (isset($request->filterStatus) && in_array($request->filterStatus, ['0', '1'])) {
            $query->where('is_active', $request->filterStatus);
        }

        $query = $query->orderBy('created_at', 'desc');

        if (!empty($query)) {
            return DataTables::of($query)->make(true);
        }
        return Datatables::of($query)->make(true);
    }


}
