<?php

namespace App\Http\Controllers;

use App\Models\Lockup;
use App\Models\Attribute;
use App\Models\LockupType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\storeAttributeRequest;
use App\Http\Requests\updateAttributeRequest;

class AttributeController extends Controller
{

    public function attributeIndex()
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
        $fieldTypes = Lockup::all();
        return view('admin.stores.attributes.product_attributes.productAttributes_list',compact('fieldTypes'));
    }

    public function attributeCreate()
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
        $fieldType = LockupType::find(1)->lockup;
        return view('admin.stores.attributes.product_attributes.create_productAttributes', compact('fieldType'));
    }

    public function attributeStore(storeAttributeRequest $request)
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);

        $field_data = ($request->field_data != null) ? json_encode(collect($request->field_data)) : $request->field_data;
        $name_code = str_replace(" ", "", strtolower($request->label_name));
        $check = Attribute::where('name_code',$name_code)->first();

        if ($check != null){
            Session::flash('alert-error', __('message.Attribute_already_exist'));
            return redirect()->back()->withInput();
        }
        DB::beginTransaction();
        try {
            Attribute::create([
                'label_name' => $request->label_name,
                'name_code' => $name_code,
                'field_type' => $request->field_type,
                'field_required' => $request->field_required,
                'is_active' => isset($request->is_active) && $request->is_active == 'on'? true : false,
                'field_data' => $field_data,
            ]);
            DB::commit();
            Session::flash('alert-success', __('message.Attribute_saved_successfully'));
            return redirect()->route('admin.attributeProductList');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('####### AttributeController -> attributeStore() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back()->withInput();
        }
    }


    public function attributeEdit($id)
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
        $attribute = Attribute::with('fieldType')->find($id);
        $reservedAttribute = ['producttitle','description','tax','taxtype','discount','profit','sellingprice','profitpercentage','originalprice','manufacturerbrand','manufacturername','shortdescription','stocks','stockstatus','productimage'];
        return view('admin.stores.attributes.product_attributes.edit_productAttributes', compact('attribute','reservedAttribute'));
    }

    public function attributeUpdate(updateAttributeRequest $request,$attribute)
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1,2])) abort(404);

        $field_data = ($request->field_data != null) ? json_encode(collect($request->field_data)) : $request->field_data ;
        DB::beginTransaction();
        try {
            $result     = Attribute::findOrFail($attribute);
            $result->label_name         = $request->label_name;
            $result->is_active          = $request->has('is_active');
            $result->field_required     = $request->field_required;
            $result->field_data         = $field_data;

            $result->save();
            DB::commit();
            Session::flash('alert-success', __('message.attribute_updated_successfully'));
            return redirect()->back();
        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::error('####### AttributeController -> attributeUpdate() #######  ' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back()->withInput();
        }

    }

    public function attributeDestroy($id)
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1])) abort(404);
        try {
            $attribute = Attribute::findOrFail($id);
            $reservedAttribute = ['producttitle','description','tax','taxtype','discount','profit','sellingprice','profitpercentage','originalprice','manufacturerbrand','manufacturername','shortdescription','stocks','stockstatus','productimage'];
            if (!in_array($attribute->name_code, $reservedAttribute)){
                Attribute::findOrFail($id)->delete();
                return response()->json(['status' => 1, 'message' => 'Attribute deleted successfully']);
            }else{
                return response()->json(['status' => 0, 'message' => "Can't delet Reserved Attribute"]);
            }
        } catch (\Exception $e) {
            Log::error('###### AttributeController -> attributeDestroy() #######' . $e->getMessage());
            Session::flash('alert-error', __('message.something_went_wrong'));
            return redirect()->back();
        }
    }

    public function dataTableAtttributesTable(Request $request)
    {
        if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) return Datatables::of([])->make(true);

        # main query
                $query = Attribute::with('fieldType');

        if (isset($request->filterSearchKey) && !empty($request->filterSearchKey)) {
            $query->where(function ($query) use ($request) {
                $query->where('label_name', 'like', '%' . $request->filterSearchKey . '%');
            });
        }

        #field type filter
        if (isset($request->filterFieldType) && !empty($request->filterFieldType) && $request->filterFieldType != 'all') {
            $query->where('field_type', $request->filterFieldType);
        }

        #status filter
        if (isset($request->filterStatus) && in_array($request->filterStatus, ['0', '1'])) {
            $query->where('is_active', $request->filterStatus);
        }

        $query = $query->orderBy('label_name', 'asc');
        if (!empty($query)) {
            return DataTables::of($query)->make(true);
          }
        return DataTables::of($query)->make(true);
    }
}
