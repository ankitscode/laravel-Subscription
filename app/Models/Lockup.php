<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\MyAutiting;
use App\User;

class Lockup extends Model implements Auditable
{
    use SoftDeletes;
    use HasTranslations;
    use \OwenIt\Auditing\Auditable;
    use MyAutiting;

    public $translatable = ['name','other'];

    protected $fillable = [
        'name',
        'lockup_type_id',
        'is_active',
        'other',
        'updated_by',
    ];

    protected $casts = [
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $hidden = [
        'is_active',
        'lockup_type_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
    ];

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function lockup_type()
    {
        return $this->belongsTo(\App\LockupType::class, 'lockup_type_id', 'id');
    }

    // Not Recommended
    public static function getByType($id)
    {
        return Lockup::where('lockup_type_id', $id)->where('is_active', true)->get()->pluck('name', 'id')->toArray();
    }

    // getByTypeKey('dose_type') array
    public static function getByTypeKey($key)
    {
        $lockup = LockupType::where('key', $key)->first();
        if (!empty($lockup)) {
            return Lockup::where('lockup_type_id', $lockup->id)->where('is_active', true)->get()->pluck('name', 'id')->toArray();
        } else {
            return [];
        }
    }

    // getLockupByKey (collection)
    public static function getLockupByKey($key)
    {
        $lockup = LockupType::where('key', $key)->first();
        if (!empty($lockup)) {
            return Lockup::select('id', 'name')->where('lockup_type_id', $lockup->id)->where('is_active', true)->get();
        } else {
            return [];
        }
    }

    public static function lockupName($lockupId)
    {
        $lockup_name = self::find($lockupId);
        return isset($lockup_name) ? $lockup_name->name : '';
    }
}
