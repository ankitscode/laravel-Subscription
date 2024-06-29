<?php

namespace App\Models;

use App\Traits\MyAutiting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    use MyAutiting;

    protected $cast = [
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'name',
        'price',
        'description',
        'is_active',
        'media_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $appends = ['product_image'];

    public function getProductImageAttribute()
    {
        if ($this->media)
            return asset(config('image.api_product_image_path_view').$this->media->thumbnail_name);
        return '';
    }

    // protected $appends = ['lang_data'];

    // public function getTypeAttribute($value)
    // {
    //     switch ($value) {
    //         case 1:
    //             return "Simple Product";
    //             break;
    //         case 2:
    //             return "Configured Product";
    //             break;

    //         default:
    //             return "Invalid Type";
    //             break;
    //     }
    // }

    // public function getIsFavoriteAttribute()
    // {
    //     if (Auth::guard('api')->check() && $this->favoriteProduct->where('id', Auth::guard('api')->user()->id)->first()) {
    //         return 1;
    //     } else {
    //         return 0;
    //     }
    // }

    // public function getIsRemindMeAttribute()
    // {
    //     if (Auth::guard('api')->check() && $this->customeRemindMeProduct->where('id', Auth::guard('api')->user()->id)->first()) {
    //         return 1;
    //     } else {
    //         return 0;
    //     }
    // }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // public function attributeSet()
    // {
    //     return $this->belongsTo(AttributeSet::class);
    // }

    // public function vendor()
    // {
    //     return $this->belongsTo(User::class, 'vendor_id');
    // }

    // public function categories()
    // {
    //     return $this->belongsToMany(Category::class);
    // }

    // public function productAttribute()
    // {
    //     return $this->belongsToMany(Attribute::class, 'product_attribute', 'product_id', 'attribute_id')->withPivot(['data','ar_data']);
    // }


    // public function productCustomAttribute()
    // {
    //     return $this->belongsToMany(Attribute::class, 'product_custom_attribute_id', 'product_id', 'attribute_id');
    // }

    // public function getLangDataAttribute()
    // {
    //     $data = collect($this->productAttribute)->map(function($value,$index){
    //         if (($value->field_type == 1 || $value->field_type == 2) && $value->name_code != 'sku' ){
    //             $lang_array = ['en'=>$value->pivot->data,'ar'=>$value->pivot->ar_data];
    //             unset($value->pivot->ar_data);
    //             $value->pivot->data = $lang_array;
    //         }
    //         return $value;
    //     });
    //     dd($data->toArray());
    //     return $data;
    // }

    // public function product_variation()
    // {
    //     return $this->hasMany(ProductVariation::class);
    // }

    // public function favoriteProduct()
    // {
    //     return $this->belongsToMany(User::class, 'customer_favorite_product', 'product_id', 'user_id');
    // }

    // public function customeRemindMeProduct()
    // {
    //     return $this->belongsToMany(User::class, 'customer_remind_me_product', 'product_id','user_id')->withPivot('product_variant_id');
    // }

    public function media()
    {
        return $this->belongsTo(Media::class,'media_id');
    }

    public static function galleryMedia($id)
    {
        $query = DB::table('product_attribute')
                ->select('data')
                ->where('product_attribute.attribute_id', '=', 18)
                ->where('product_attribute.product_id', '=', $id)
                ->first();
        if (!isset($query)){
            return null;
        }
        $data = collect(json_decode($query->data, true))->map(function ($n){
            $image = Media::where('id',$n)->select('name','thumbnail_name')->first();
            if (!isset($image) && empty($image)){
                return null;
            }
            $image['name'] = asset(config('image.api_product_image_path_view').$image->name);
            $image['thumbnail_name'] = asset(config('image.api_product_image_path_view').$image->thumbnail_name);
            return $image->toArray();
        });
        return $data;
    }
}
