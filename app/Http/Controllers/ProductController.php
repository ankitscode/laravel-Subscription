<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Models\VariationType;
use App\Models\ProductVariation;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductDetailsDownload;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function productIndex()
    {
        if (!auth_permission_check('View All Products')) abort(404);
        $product=product::get();
        // dd($product);
        return view('admin.catalog.products.product_list',compact('product'));
    }


    public function productCreate()
    {
        if (!auth_permission_check('Create Product')) abort(404);
        try {
            return view('admin.catalog.products.create_product');
        } catch (\Exception $e) {
        Log::error('##### ProductController -> productView() #####' .$e->getMessage());
        Session::flash('alert-error', __('message.something_went_wrong'));
        return redirect()->back();
        }
    }

    public function productStore(Request $request)
    {
      if (!auth_permission_check('Create Product')) abort(404);
      try {
        DB::beginTransaction();

        $product = Product::Create([
            'name' => $request->product_name,
            'price' => $request->price,
            'description' => $request->description,
            'is_active' => isset($request->is_active) && $request->is_active == 'on' ? true : false,
        ]);

        if ($request->hasFile('image')) {
            $path  = config('image.product_image_path_store');
            $media = CommenController::saveImage($request->image, $path);
            $product->media_id = $media;
            $product->save();
        }

        DB::commit();
        Session::flash('alert-success', __('message.image_updated_successfully'));
        return redirect()->route('admin.productList');
      } catch (\Exception $e) {
        DB::rollBack();
      Log::error('##### ProductController -> productView() #####' .$e->getMessage());
      Session::flash('alert-error', __('message.something_went_wrong'));
      return redirect()->back();
      }

    }



//   public function productCreate()
//   {

//     if (!auth_permission_check('Create Product')) abort(404);
//     $categories = Category::where('is_active', 1)->get();
//     $attributes = Attribute::where('is_active', 1)->orderBy('rank','asc')->get();
//     $variationTypes = VariationType::get();
//     $googleMapApiKey = Configuration::where('name', 'google_map_api_key')->first();

//     return view('admin.catalog.products.create_product', compact('attributes', 'categories', 'variationTypes' ,'googleMapApiKey'));
//   }

//   public function variationValue(Request $request)
//   {
//     if (!isset($request->selectedVariation)){
//       return response()->json(['status'=> 1, 'totalPrice'=> 0]);
//     }

//     try {
//       $variationValue = collect($request->selectedVariation)->map(function($n){
//         $data = VariationType::with(['variation'=>function($query){
//           $query->where('is_active',1);
//         }])->where('id', $n)->first();
//         return (isset($data) && !empty($data)) ? $data : null;
//       });
//       return response()->json(['status'=>1, 'data'=>$variationValue],Response::HTTP_OK);
//     } catch (\Exception $e) {
//       Log::error('##### ProductController->variationValure #####'. $e->getMessage());
//       Session::flash('alert-error', __('message.something_went_wrong'));
//       return response()->json(['status'=>0, 'message'=> __('message.something_went_wrong')],Response::HTTP_INTERNAL_SERVER_ERROR);
//     }
//   }

//   public function productStore(Request $request)
//   {

//     /**
//      * location_name is the name provided by google api
//      * complete_location_name is the address visible in input field while entering the data .
//      * It provide the complete detailed address.
//      *
//      */
//     $validData = validator::make($request->all(), [
//         'location_name'  => 'required',
//         'location_latitude'  => 'required',
//         'location_longitude'  => 'required',
//     ]);

//     if ($validData->fails()) {
//         Session::flash('alert-error', $validData->getMessageBag()->first());
//         return redirect()->back();
//     }


//     if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
//     DB::beginTransaction();
//     try {

//       $product = Product::create([
//         'is_active' => isset($request->is_active) && $request->is_active == 'on' ? true : false,
//         'type' => isset($request->variant) ? 2 : 1,
//         'location_name' => $request->complete_location_address,
//         'location_latitude' =>$request->location_latitude,
//         'location_longitude' => $request->location_longitude,
//       ]);

//       $data = self::ProductAttributeData($request->data);

//       $product->productAttribute()->attach($data);
//       $product->categories()->attach($request->categories);

//       if ($request->hasFile('image')){
//         $image = collect($request->image)->map(function ($n) {
//             $path  = config('image.product_image_path_store');
//             $media = CommenController::saveImage($n, $path);
//             return ['data' => $media];
//         });
//         $product->productAttribute()->attach($image);
//       }
//       if ($request->has('multipleImage')){
//         $multipleImage = collect($request->multipleImage)->map(function ($n) {
//             $images = collect($n)->map(function ($q){
//                 $path  = config('image.product_image_path_store');
//                 $media = CommenController::saveImage($q, $path);
//                 return $media;
//             })->toArray();
//             return ['data' => json_encode($images)];
//         });
//         $product->productAttribute()->attach($multipleImage);
//       }
//       if ($request->has('variant')){
//         $variant = $request->variant;
//         foreach ($variant as $key => $variantValue) {
//             if (isset($variantValue['image']) && !empty($variantValue['image'])){
//                 $path  = config('image.product_image_path_store');
//                 $variantImage = CommenController::saveImage($variantValue['image'], $path);
//             }
//             $productVariation = ProductVariation::create([
//                 'product_id'            => $product->id,
//                 'image'                 => isset($variantImage) && !empty($variantImage) ? $variantImage : null,
//                 'sku'                   => $variantValue['SKU'],
//                 'original_price'        => $variantValue['original_price'],
//                 'profit_percentage'     => $variantValue['profit_percentage'],
//                 'profit'                => $variantValue['profit'],
//                 'price'                 => $variantValue['price'],
//                 'discount'              => $variantValue['discount'],
//                 'quantity'              => $variantValue['quantity'],
//                 'is_active'             => isset($variantValue['is_active']) && $variantValue['is_active'] == 'on' ? true : false,
//                 'variation_combination' => $variantValue['combination'],

//             ]);
//             if (isset($variantValue['combination']) && !empty($variantValue['combination'])){
//                 $productVariation->product_variation_value()->attach(json_decode($variantValue['combination']));
//             }
//         }
//       }

//       DB::commit();
//       Session::flash('alert-success', __('message.Product_saved_successfully'));
//       return redirect()->route('admin.productList');
//     } catch (\Exception $e) {
//         DB::rollBack();
//       Log::error('####### ProductController -> productStore() #######  ' . $e->getMessage());
//       Session::flash('alert-error', __('message.something_went_wrong'));
//       return redirect()->back()->withInput();
//     }
//   }


  public function productView($id)
  {
    if (auth_permission_check('View All Products'));
    try {
        $productDetails = Product::find($id);
        // dd( $productDetail);
        $productDetail = self::productWithAllDetails($id,false);
        
        return view('admin.catalog.products.view_product', compact('productDetails','productDetail'));
     } catch (\Exception $e) {
         Log::error('##### ProductController -> productView() #####' .$e->getMessage());
         Session::flash('alert-error', __('message.something_went_wrong'));
         return redirect()->back();
     }
  }


  public function productEdit($id)
  {
    // if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);

    // try {
        // $productDetails = Product::with('categories','productAttribute','product_variation.product_variation_value.variation_type')->findOrFail($id);
        // $productDetails->image = $productDetails->media($id);
        // $productDetails->gallery = $productDetails->galleryMedia($id);

        // $categories = Category::where('is_active', 1)->get();
        // $attributes = Attribute::where('is_active', 1)->orderBy('rank','asc')->get();
        // $googleMapApiKey = Configuration::where('name', 'google_map_api_key')->first();
        // $productAttribute = collect($productDetails->productAttribute);

        // $merged1 = collect($attributes)->map(function($n) use ($productAttribute) {
        //    $item =  $productAttribute->first(function ($value, $key) use ($n){
        //         return $value['id'] === $n['id'];
        //     });
        //     return (isset($item)) ? $item : $n ;
        // });

        // if ($productDetails->type == "Configured Product" && isset($productDetails->product_variation) && !empty($productDetails->product_variation) && $productDetails->product_variation != '[]')
        // {
        //     $product_variation = collect($productDetails->product_variation);
        //     $combinationData = self::productVariationValue($productDetails->product_variation);
        //     $combination = (isset($combinationData[0]) && !empty($combinationData[0])) ? $combinationData[0]->toArray() : null ;
        //     $variantName = (isset($combinationData[1]) && !empty($combinationData[1])) ? $combinationData[1] : null ;
        //     $ths = array_keys($combination);
        //     array_unshift($ths,"SKU");
        //     $tds = collect(self::build(array_values($combination)))->map(function($n,$a) use($product_variation){
        //             array_unshift($n,$product_variation[$a]->sku);
        //         return $n;
        //     })->toArray();
        //     return view('admin.catalog.products.edit_product', compact('id','merged1','productDetails', 'categories','ths','tds','product_variation','googleMapApiKey'));
        // }

        // return view('admin.catalog.products.edit_product', compact('id','merged1','productDetails', 'categories','googleMapApiKey'));
    // } catch (\Exception $e) {
    //     Log::error('####### ProductController -> productEdit() #######  ' . $e->getMessage());
    //     Session::flash('alert-error', __('message.something_went_wrong'));
    //     return redirect()->back()->withInput();
    // }
  }

//   public function productUpdate(Request $request, $productId)
//   {
//     if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);

//     $validData = validator::make($request->all(), [
//         'location_name'  => 'required',
//         'location_latitude'  => 'required',
//         'location_longitude'  => 'required',
//     ]);

//     if ($validData->fails()) {
//         Session::flash('alert-error', $validData->getMessageBag()->first());
//         return redirect()->back();
//     }

//     $productImages = Product::media($productId);
//     $productGalleryImages = DB::table('product_attribute')->where([['product_id',$productId],['attribute_id','19']])->select('data')->first();

//     DB::beginTransaction();
//     try {
//       $product                      = Product::find($productId);
//       $product->is_active           = $request->has('is_active');

//       //location update start
//       $product->location_name = $request->complete_location_address;
//       $product->location_latitude =$request->location_latitude;
//       $product->location_longitude = $request->location_longitude;
//     //location update end

//       $product->save();

//       $data = self::ProductAttributeData($request->data);

//       $product->productAttribute()->sync($data);
//       $product->categories()->sync($request->categories);
//         #todo not updating  image
//       if ($request->hasFile('image')) {
//         $image = collect($request->image)->map(function ($n) {
//           $path  = config('image.product_image_path_store');
//           $media = CommenController::saveImage($n, $path);
//           return ['data' => $media];
//         });
//         $product->productAttribute()->attach($image);
//       } else {
//         $product->productAttribute()->attach([3=>['data'=>$productImages->id]]);
//       }

//       if ($request->has('multipleImage')){
//         $multipleImage = collect($request->multipleImage)->map(function ($n) {
//             $images = collect($n)->map(function ($q){
//                 $path  = config('image.product_image_path_store');
//                 $media = CommenController::saveImage($q, $path);
//                 return $media;
//             })->whereNotNull()->toArray();
//             return ['data' => json_encode($images)];
//         });
//         $product->productAttribute()->attach($multipleImage);
//       } else {
//         isset($productGalleryImages) ? $product->productAttribute()->attach([19=>['data'=>$productGalleryImages->data]]) : '' ;
//       }

//         if ($request->has('variant')){
//             $variant = $request->variant;
//             foreach ($variant as $key => $variantValue) {
//                 $productVariation = ProductVariation::find($key);

//                 if (isset($variantValue['image']) && !empty($variantValue['image'])){
//                     $path  = config('image.product_image_path_store');
//                     $productVariation->image = CommenController::saveImage($variantValue['image'], $path);
//                 }
//                 $productVariation->price                      = $variantValue['price'];
//                 $productVariation->quantity                   = $variantValue['quantity'];
//                 $productVariation->original_price             = $variantValue['original_price'];
//                 $productVariation->profit_percentage          = $variantValue['profit_percentage'];
//                 $productVariation->profit                     = $variantValue['profit'];
//                 $productVariation->discount                   = $variantValue['discount'];
//                 $productVariation->is_active = isset($variantValue['is_active']) && $variantValue['is_active'] == 'on' ? true : false;
//                 $productVariation->save();
//             }
//         }

//       DB::commit();
//       return redirect()->route('admin.viewProduct', ['id' => $productId])->with('success', 'Product updated successfully');
//     } catch (\Exception $e) {
//         DB::rollBack();
//       Log::error('####### ProductController -> productUpdate() #######  ' . $e->getMessage());
//       Session::flash('alert-error', __('message.something_went_wrong'));
//       return redirect()->back()->withInput();
//     }
//   }


  public function productDestroy($id)
  {
    // if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);
    if(auth_permission_check('Delete Product'));
    DB::beginTransaction();
    try {
      Product::where('id', $id)->delete();
      DB::commit();
      Session::flash('alert-success', __('message.product_ deleted_successfully'));
      return redirect()->back();
    } catch (\Exception $e) {
        DB::rollBack();
      Log::error('####### ProductController -> productDestroy() #######  ' . $e->getMessage());
      Session::flash('alert-error', __('message.something_went_wrong'));
      return response()->json(['status' => __('message.something_went_wrong')]);
    }
  }



  public function dataTableproducts(Request $request)
  {
    if (!auth_permission_check('View All Products')) abort(404);
    // $query = Product::all();
    // if (!empty($query)) {
    //   return DataTables::of($query)->make(true);
    // }
    // return DataTables::of([])->make(true);
    return Datatables::of(Product::query())
    ->addColumn('Action', function ($product) {
        $link = '<a href="' . route('admin.editProduct', $product->id) . '" class="ri-pencil-fill fs-16 btn-sm"></a> ' .
            '<a href="' . route('admin.viewProduct', $product->id) . '" class="ri-eye-fill fs-16 btn-sm"></a> ' .
            '<a href="' . route('admin.destroyProduct', $product->id) . '"class="ri-delete-bin-5-fill fs-16 text-danger"></a>';
        return $link;
    })
    ->rawColumns(['Action'])
    ->make(true);

  }

    #download
    // public function download(Request $request)
    // {
    //     if (!Auth::user() || !in_array(Auth::user()->user_type, [1, 2])) abort(404);

    //     $validData = validator::make($request->all(), [
    //         'filter_date'  => 'required',
    //     ]);
    //     if ($validData->fails()) {
    //         Session::flash('alert-error', $validData->getMessageBag()->first());
    //         return redirect()->back();
    //     }

    //     try {
    //       $dateRange = isset($request->filter_date) ? explode(" ",$request->filter_date) : null ;
    //       $from   = $dateRange[0];
    //       $to     = $dateRange[2];

    //       $query = Product::with('order_items', 'orderStatus', 'paymentMethod','paymentStatus')
    //               ->whereBetween('created_at', array($from, $to));

    //       #status filter
    //     //   if (isset($request->order_status) && $request->order_status !== 'all') {
    //     //       $query->where('order_status', $request->order_status);
    //     //   }
    //         $orders = $query->orderBy('created_at', 'asc')->get();
    //         $orderDetails = collect($orders)->map(function($n){
    //             return self::productWithAllDetails($n->id);
    //         })->toArray();
    //       dd($orders->toArray(),$orderDetails);
    //         #excel
    //         if ($request->download_type == 'csv') {
    //             return Excel::download(new ProductDetailsDownload($orders), 'products_' . time() . '.csv');
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('####### BatteryController -> download() ####### ' . $e->getMessage());
    //         Session::flash('alert-error', __('message.something_went_wrong'));
    //         return redirect()->back();
    //     }
    // }
//   ==================================================================================

    // public static function productById($id)
    // {
    //     $productDetails = Product::with('categories','productAttribute')->where('id', $id)->first();
    //     if (!isset($productDetails) || empty($productDetails)){
    //         return null;
    //     }
    //     $productDetails->image = $productDetails->apiMedia($id);
    //     return $productDetails;
    // }

    // public static function productByIdWithDetails($id)
    // {
    //     $productDetails = Product::with('categories','productAttribute')->where('is_active', 1)->where('id', $id)->get();
    //     if (!isset($productDetails) || empty($productDetails)){
    //         return null;
    //     }
    //     $productDetail = collect($productDetails)->map(function($n){
    //         $n->image = $n->apiMedia($n->id);
    //         $map = collect($n->productAttribute)->map(function($a){
    //             if ($a->pivot->attribute_id == '1'){
    //                 return ['productTitle' => $a->pivot->data];
    //             }
    //             if ($a->pivot->attribute_id == '2'){
    //                 return ['productDescription' => $a->pivot->data];
    //             }
    //             if ($a->pivot->attribute_id == '4'){
    //                 return ['stockStatus' => $a->pivot->data];
    //             }
    //             if ($a->pivot->attribute_id == '5'){
    //                 return ['stocks' => $a->pivot->data];
    //             }
    //             if ($a->pivot->attribute_id == '6'){
    //                 return ['shortDescription' => $a->pivot->data];
    //             }
    //             if ($a->pivot->attribute_id == '7'){
    //                 return ['manufacturerName' => $a->pivot->data];
    //             }
    //             if ($a->pivot->attribute_id == '8'){
    //                 return ['manufacturerBrand' => $a->pivot->data];
    //             }
    //             if ($a->pivot->attribute_id == '9'){
    //                 return ['originalPrice' => $a->pivot->data];
    //             }
    //             if ($a->pivot->attribute_id == '10'){
    //                 return ['profitPercentage' => $a->pivot->data];
    //             }
    //             if ($a->pivot->attribute_id == '11'){
    //                 return ['sellingPrice' => $a->pivot->data];
    //             }
    //             if ($a->pivot->attribute_id == '12'){
    //                 return ['profit' => $a->pivot->data];
    //             }
    //             if ($a->pivot->attribute_id == '13'){
    //                 return ['discount' => $a->pivot->data];
    //             }
    //         })->whereNotNull()->collapse()->toArray();
    //           return ['productId'=>$n['id'],
    //                   'is_active'=>$n['is_active'],
    //                   'productImage'=>$n['image'],
    //                   'productName'=>isset($map['productTitle']) ? $map['productTitle'] : null,
    //                   'productDescription'=>isset($map['productDescription']) ? $map['productDescription'] : null,
    //                   'stockStatus'=>isset($map['stockStatus']) ? $map['stockStatus'] : null,
    //                   'shortDescription'=>isset($map['shortDescription']) ? $map['shortDescription'] : null,
    //                   'manufacturerName'=>isset($map['manufacturerName']) ? $map['manufacturerName'] : null,
    //                   'manufacturerBrand'=>isset($map['manufacturerBrand']) ? $map['manufacturerBrand'] : null,
    //                   'sellingPrice'=>isset($map['sellingPrice']) ? $map['sellingPrice'] : null,
    //                   'discount'=>isset($map['discount']) ? $map['discount'] : null,
    //                 //   'productPrice'=>$map['productPrice'],
    //                 ];
    //     });
    //     return $productDetail;
    // }

    public static function productWithAllDetails($id,$is_active = true)
    {
        try {
            $query = Product::where('id',$id);

            if ($is_active === false){
            $query = $query->with(['categories'=>function($innerQuery){
                        $innerQuery->select('*')->where('is_active', 1);
                    },'productAttribute','product_variation.product_variation_value.variation_type'])->get();
            }else{
            $query = $query->where('is_active', 1)->with(['categories'=>function($innerQuery){
                        $innerQuery->select('*')->where('is_active', 1);
                    },'productAttribute','product_variation.product_variation_value.variation_type'])->get();
            }

            if (!isset($query) && empty($query)){
                Log::error('#### API #### static ProductController -> productWithAllDetails() ####### no Active product found or id doesn\'t exist ');
                return null;
            }

            $productDetails = collect($query)->map(function($n) use($is_active){
                $n->image = $n->apiMedia($n->id);
                $n->gallery = $n->galleryMedia($n->id);
                $map = collect($n->productAttribute)->map(function($a){
                    if (($a->field_type == 1 || $a->field_type == 2 ) && $a->name_code != 'sku'){
                        $newIndexValue['en'] = isset($a->pivot->data)?$a->pivot->data:'';
                        $newIndexValue['ar'] = isset($a->pivot->ar_data)?$a->pivot->ar_data:'';
                    }else{
                        $newIndexValue = $a->pivot->data;
                    }
                    return [$a->name_code => $newIndexValue];
                })->whereNotNull()->collapse()->toArray();

                $category = collect($n->categories)->map(function($b) use($is_active){
                    unset($b->pivot);
                    unset($b->is_menu);
                    unset($b->is_active);
                    return $b;
                })->toArray();
                if ($n->type == "Configured Product"){
                    if ($n->product_variation != '[]' && isset($n->product_variation) && !empty($n->product_variation) ){
                        $productVariation = ($is_active) ? $n->product_variation->where('is_active',1) : $n->product_variation;
                        $combination = self::productVariationValue($productVariation,$map['producttitle'][((Session::get('locale') != null) && !empty(Session::get('locale'))) ? Session::get('locale') : 'en' ]);
                        $n->combination = $combination[0];
                    }
                }

                $map['productId']           = $n['id'];
                $map['is_active']           = $n['is_active'];
                $map['type']                = $n['type'];
                $map['category']            = $category;
                $map['productimage']        = $n->image;
                $map['productgallery']      = $n->gallery;
                if($n['type'] == "Configured Product"){
                    $map['product_variation']          = isset($productVariation) ? $productVariation->map(function($n,$a)use($combination){$n['variant_Name'] = $combination[1][$a];return $n;}) : null;
                    $map['combination']                = $n['combination'];
                }

                return $map;
            })->collapse();
            return $productDetails;
        } catch (\Exception $e) {
            Log::error('#### API #### static ProductController -> productWithAllDetails() #######  ' . $e->getMessage());
            return null;
        }
    }

    // public static function productVariationValue($data,$productName = null)
    // {
    //     try {
    //         foreach ($data as $key => $value) {
    //             $variationValue[$key] = $value->product_variation_value;
    //             $variantName[$key] = $productName . '('.$value['product_variation_value']->pluck('name')->implode(',').')';
    //             unset($value->product_variation_value);
    //         }
    //         $combination = collect($variationValue)->collapse()->groupBy(function ($item, $key) {
    //             return $item['variation_type']['key'];
    //         })->map(function($n,$i){
    //             $data =  $n->map(function($d,$i){
    //                 return (['id'=> $d['id'],'name'=>$d['name'],'other'=>$d['other']]);
    //             });
    //             return $data->unique()->values();
    //         });
    //         return [$combination,$variantName];
    //     } catch (\Exception $e) {
    //         Log::error('#### API #### static ProductController -> productVariationValue() #######  ' . $e->getMessage());
    //         return null;
    //     }

    // }

    // public static function build($set)
    // {
    //     if (!$set) {
    //         return array(array());
    //     }
    //     $subset = array_shift($set);
    //     $cartesianSubset = self::build($set);

    //     $result = array();
    //     foreach ($subset as $value) {
    //         foreach ($cartesianSubset as $p) {
    //             array_unshift($p, $value);
    //             $result[] = $p;
    //         }
    //     }

    //     return $result;
    // }

    // public function ProductAttributeData($data)
    // {
    //     $productAttributeData = collect($data)->map(function ($n) {
    //         if (gettype($n) == 'array') {
    //             $en_value = $n['en'];
    //             $ar_value = $n['ar'];
    //         }else{
    //             $en_value = $n;
    //             $ar_value = null;
    //         }
    //         return ['data' => isset($en_value) ? $en_value : '','ar_data' => isset($ar_value) ? $ar_value : ''];
    //     });
    //     return $productAttributeData;

    // }

    // public static function getProductAllDetailsByName($id,$is_active = true)
    // {
    //     try {
    //         $query = Product::where('id',$id);

    //         if ($is_active === false){
    //         $query = $query->with(['categories'=>function($innerQuery){
    //                     $innerQuery->select('*')->where('is_active', 1);
    //                 },'productAttribute','product_variation.product_variation_value.variation_type'])->get();
    //         }else{
    //         $query = $query->where('is_active', 1)->with(['categories'=>function($innerQuery){
    //                     $innerQuery->select('*')->where('is_active', 1);
    //                 },'productAttribute','product_variation.product_variation_value.variation_type'])->get();
    //         }

    //         if (!isset($query) && empty($query)){
    //             Log::error('#### API #### static ProductController -> getProductAllDetailsByName() ####### no Active product found or id doesn\'t exist ');
    //             return null;
    //         }

    //         $productDetails = collect($query)->map(function($n) use($is_active){
    //             $n->image = $n->apiMedia($n->id);
    //             $n->gallery = $n->galleryMedia($n->id);
    //             $map = collect($n->productAttribute)->map(function($a){
    //                 if (($a->field_type == 1 || $a->field_type == 2 ) && $a->name_code != 'sku'){
    //                     $newIndexValue['en'] = isset($a->pivot->data)?$a->pivot->data:'';
    //                     $newIndexValue['ar'] = isset($a->pivot->ar_data)?$a->pivot->ar_data:'';
    //                 }else{
    //                     $newIndexValue = $a->pivot->data;
    //                 }
    //                 return [$a->name_code => $newIndexValue];
    //             })->whereNotNull()->collapse()->toArray();

    //             $category = collect($n->categories)->map(function($b) use($is_active){
    //                 unset($b->pivot);
    //                 unset($b->is_menu);
    //                 unset($b->is_active);
    //                 return $b;
    //             })->toArray();
    //             if ($n->type == "Configured Product"){
    //                 if ($n->product_variation != '[]' && isset($n->product_variation) && !empty($n->product_variation) ){
    //                     $productVariation = ($is_active) ? $n->product_variation->where('is_active',1) : $n->product_variation;
    //                     $combination = self::productVariationValue($productVariation,$map['producttitle'][((Session::get('locale') != null) && !empty(Session::get('locale'))) ? Session::get('locale') : 'en' ]);
    //                     $n->combination = $combination[0];
    //                 }
    //             }

    //             $map['productId']           = $n['id'];
    //             $map['is_active']           = $n['is_active'];
    //             $map['type']                = $n['type'];
    //             $map['category']            = $category;
    //             $map['productimage']        = $n->image;
    //             $map['productgallery']      = $n->gallery;
    //             if($n['type'] == "Configured Product"){
    //                 $map['product_variation']          = isset($productVariation) ? $productVariation->map(function($n,$a)use($combination){$n['variant_Name'] = $combination[1][$a];return $n;}) : null;
    //                 $map['combination']                = $n['combination'];
    //             }

    //             return $map;
    //         })->collapse();
    //         return $productDetails;
    //     } catch (\Exception $e) {
    //         Log::error('#### API #### static ProductController -> getProductAllDetailsByName() #######  ' . $e->getMessage());
    //         return null;
    //     }
    // }
}
