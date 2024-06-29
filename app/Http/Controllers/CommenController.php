<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Media;
use App\Models\Order;
use App\Models\Lockup;
use Illuminate\Support\Str;
use App\Models\Configuration;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CommenController extends Controller
{
    public static function saveImage($image, $path)
    {
        $path  =  base_path($path);

        if ($image->isValid() && in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
            $image_extension    = $image->getClientOriginalExtension();
            $image_size         = $image->getSize();
            $type               = $image->getMimeType();

            $new_name           = rand(1111, 9999) . date('mdYHis') . uniqid() . '.' . $image_extension;
            $thumbnail_name     = 'thumbnail_' . rand(1111, 9999) . date('mdYHis') . uniqid() . '.' .  $image_extension;

            #save thumbnail
            $thumbnail = Image::make($image->getRealPath());

            $thumbnail->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path . '/' . $thumbnail_name);

            #save original
            $image->move($path, $new_name);

            $mediaArray = [
                'type' => $type,
                'file_size' => $image_size,
                'name' => $new_name,
                'thumbnail_name' => $thumbnail_name,
            ];
            $media = self::saveMediaData($mediaArray);

            return !empty($media) ? $media->id : null;
        } else {
            Log::error('####### CommenController -> saveImage() #######');
            return null;
        }
    }

    public static function saveImageBase64($image, $path)
    {
        $path  =  base_path($path);

        if ($image) {
            $image_extension    = explode('/', mime_content_type($image))[1];
            $image_size         = (int)(strlen(rtrim($image, '=')) * 0.75);
            $mimeData           = getimagesize($image);
            $type               = $mimeData['mime'];

            $new_name           = rand(1111, 9999) . date('mdYHis') . uniqid() . '.' . $image_extension;
            $thumbnail_name     = 'thumbnail_' . rand(1111, 9999) . date('mdYHis') . uniqid() . '.' .  $image_extension;

            #save original
            $thumbnail = Image::make($image)->save($path . '/' . $new_name);

            #save thumbnail
            $thumbnail->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path . '/' . $thumbnail_name);

            $mediaArray = [
                'type' => $type,
                'file_size' => $image_size,
                'name' => $new_name,
                'thumbnail_name' => $thumbnail_name,
            ];
            $media = self::saveMediaData($mediaArray);

            return !empty($media) ? $media->id : null;
        } else {
            Log::error('####### CommenController -> saveImageBase64() #######');
            return null;
        }
    }


    public static function saveMediaData($mediaArray)
    {
        try {
            $media = Media::create($mediaArray);
            return $media;
        } catch (\Exception $e) {
            Log::error('####### CommenController -> saveMediaData() #######  ' . $e->getMessage());
            return null;
        }
    }

    public static function profileImage($image)
    {
        if (isset($image) && !empty($image)) {
            return url(config('image.api_profile_image_path_view') . $image);
        } else {
            return null;
        }
    }

    public static function generate_uuid()
    {
        $uuid = (string) Str::uuid();
        if (self::uuid_exists($uuid)) {
            return  self::generate_uuid();
        }
        return $uuid;
    }
    public static function uuid_exists($uuid)
    {
        return User::where('uuid', $uuid)->exists();
    }

    public static function generate_user_code()
    {
        $user_code = rand(1000000000, 9999999999);
        if (self::user_code_exists($user_code)) {
            return  self::generate_user_code();
        }
        return $user_code;
    }
    public static function user_code_exists($user_code)
    {
        return User::where('code', $user_code)->exists();
    }

    public static function imageUrl($path, $image)
    {
        if (isset($image) && !empty($image)) {
            return url($path . $image);
        } else {
            return null;
        }
    }

    public static function generate_otp()
    {
        $code = rand(1111, 9999);
        if (self::generate_otp_exists($code)) {
            return  self::generate_otp();
        }
        return $code;
    }
    public static function generate_otp_exists($code)
    {
        return User::where('otp', $code)->exists();
    }

    public static function productDetails($query)
    {
        $productDetails = collect($query)->map(function($n){
            $n->image = $n->apiMedia($n->id);
            $map = collect($n->productAttribute)->map(function($a){
                if ($a->pivot->attribute_id == '1'){
                    return ['productTitle' => $a->pivot->data];
                }
                if ($a->pivot->attribute_id == '2'){
                    return ['productDescription' => $a->pivot->data];
                }
                if ($a->pivot->attribute_id == '4'){
                    return ['stockStatus' => $a->pivot->data];
                }
                if ($a->pivot->attribute_id == '5'){
                    return ['stocks' => $a->pivot->data];
                }
                if ($a->pivot->attribute_id == '6'){
                    return ['shortDescription' => $a->pivot->data];
                }
                if ($a->pivot->attribute_id == '7'){
                    return ['manufacturerName' => $a->pivot->data];
                }
                if ($a->pivot->attribute_id == '8'){
                    return ['manufacturerBrand' => $a->pivot->data];
                }
                if ($a->pivot->attribute_id == '9'){
                    return ['originalPrice' => $a->pivot->data];
                }
                if ($a->pivot->attribute_id == '10'){
                    return ['profitPercentage' => $a->pivot->data];
                }
                if ($a->pivot->attribute_id == '11'){
                    return ['sellingPrice' => $a->pivot->data];
                }
                if ($a->pivot->attribute_id == '12'){
                    return ['profit' => $a->pivot->data];
                }
                if ($a->pivot->attribute_id == '13'){
                    return ['discount' => $a->pivot->data];
                }
            })->whereNotNull()->collapse()->toArray();
              return ['productId'=>$n['id'],
                      'is_active'=>$n['is_active'],
                      'productImage'=>$n['image'],
                      'productName'=>isset($map['productTitle']) ? $map['productTitle'] : null,
                      'productDescription'=>isset($map['productDescription']) ? $map['productDescription'] : null,
                      'stockStatus'=>isset($map['stockStatus']) ? $map['stockStatus'] : null,
                      'shortDescription'=>isset($map['shortDescription']) ? $map['shortDescription'] : null,
                      'manufacturerName'=>isset($map['manufacturerName']) ? $map['manufacturerName'] : null,
                      'manufacturerBrand'=>isset($map['manufacturerBrand']) ? $map['manufacturerBrand'] : null,
                      'sellingPrice'=>isset($map['sellingPrice']) ? $map['sellingPrice'] : null,
                      'discount'=>isset($map['discount']) ? $map['discount'] : null,
                    //   'productPrice'=>$map['productPrice'],
                    ];
        });
        return $productDetails;
    }

    public static function delivery_time_calculator($currentTime,$estimateDeliveryTimeInMinutes)
    {
        // $currentTime = Carbon::parse('today 23:00', 'UTC'); //test
        $startTime =  Carbon::parse('today 9am', 'UTC');
        $endTime =  Carbon::parse('today 11pm', 'UTC');
        if ($currentTime->lessThan($startTime)){
            $currentTime = Carbon::parse('today 9am', 'UTC');
        }elseif ($currentTime->greaterThan($endTime)) {
            $currentTime = Carbon::parse('tomorrow 9am', 'UTC');
            $startTime =  Carbon::parse('tomorrow 9am', 'UTC');
            $endTime =  Carbon::parse('tomorrow 11pm', 'UTC');
        }
        $duration = $endTime->diffInMinutes($currentTime);
        if ($estimateDeliveryTimeInMinutes > $duration && $estimateDeliveryTimeInMinutes > 0){
            $newEstimateDelvTime =  $estimateDeliveryTimeInMinutes-$duration;
            $newCurrentTime = $currentTime->addMinutes($duration)->addMinutes(601);
            $deliveryTime = self::checkTime($newCurrentTime,$newEstimateDelvTime);
        }
        $deliveryTime = $currentTime->addMinutes($estimateDeliveryTimeInMinutes);
        return $deliveryTime;
    }
    public static function checkTime($currentTime,$estimateDeliveryTimeInMinutes)
    {
        $startTime =  Carbon::parse('tomorrow 9am', 'UTC');
        $endTime =  Carbon::parse('tomorrow 11pm', 'UTC');
        $startEndDiff = $endTime->diffInMinutes($startTime);

        if ($estimateDeliveryTimeInMinutes > $startEndDiff){
            $newEstimateDelvTime =  $estimateDeliveryTimeInMinutes - $startEndDiff;
            $newCurrentTime = $currentTime->addMinutes($startEndDiff)->addMinutes(601);
            $deliveryTime = self::checkTime($newCurrentTime,$newEstimateDelvTime);
        }
        $deliveryTime = $currentTime->addMinutes($estimateDeliveryTimeInMinutes);
        return $deliveryTime;

    }

    // public static function generate_order_id()
    // {
    //     $lastOrder = Order::orderBy('created_at','DESC')->first();
    //     $latestOrderId = (isset($lastOrder) && !empty($lastOrder)) ? $lastOrder->id : 0;
    //     $code = '#'.str_pad($latestOrderId + 1, 8, "0", STR_PAD_LEFT);
    //     if (self::generate_order_id_exists($code)) {
    //         return  self::generate_otp();
    //     }
    //     return $code;
    // }
    // public static function generate_order_id_exists($code)
    // {
    //     return Order::where('order_id', $code)->exists();
    // }

    // public static function configuration_value($lockup_id)
    // {
    //     $Details = Configuration::where('name', $lockup_id)->first();
    //     if (isset($Details->data) && !empty($Details->data)){
    //         $data = collect($Details->data)->map(function($n,$i){
    //             $value = Lockup::where('id',$n)->first();
    //             if ((isset($value) && !empty($value) ) && ($value != '[]')){
    //                 return $value;
    //             }
    //         })->whereNotNull();
    //         $Details->data = $data;
    //     }
    //     return $Details;
    // }

    public static function allPermissions($type = '')
    {
        $query = Permission::where('is_active', 1);

        if (!empty($type)) {
            $query->where('type', $type);
        }
        return $query->get();
    }

    public static function getRolePermission($role_id)
    {
        if (!empty($role_id)) {
            $role = Role::find($role_id);
            return $role->permissions();
        } else {
            return null;
        }
    }


    public static function showRolePermission($role_id, $type = '')
    {
        #get all permission
        $allPermissionsLists  = self::allPermissions($type);
        #get role permission ids
        if ($role_id) {
            $rolePermissions    = self::getRolePermission($role_id);
            $rolePermissions    = !empty($rolePermissions) ? $rolePermissions->pluck('id')->toArray() : null;
        } else {
            $rolePermissions = [];
        }

        return [
            'allPermissionsLists' => !empty($allPermissionsLists) ? $allPermissionsLists : null,
            'allGroups'           => !empty($allPermissionsLists) ? array_values(array_unique($allPermissionsLists->pluck('group')->toArray())) : null,
            'rolePermissions'     => $rolePermissions,
        ];
    }
}
