<?php

namespace App\Models;


use App\Models\Lockup;
// use Spatie\Permission\Models\Role;
use App\Traits\MyAutiting;
use Illuminate\Database\Eloquent\Model;
// use Spatie\Permission\Models\Permission;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;

class LockupType extends Model implements Auditable
{
    use SoftDeletes;
    use HasTranslations;
    use \OwenIt\Auditing\Auditable;
    use MyAutiting;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'key',
        'is_active',
        'is_system',
        'updated_by',
    ];

    protected $casts = [
        'created_by'    => 'integer',
        'updated_by'    => 'integer',
    ];

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function lockup()
    {
        return $this->hasMany(Lockup::class, 'lockup_type_id');
    }

    // public static function boot()
    // {
    //     parent::boot();

    //     self::created(function ($model) {

    //         Permission::create(['group' => $model->key, 'name' => 'view-' . $model->key . '-list']);
    //         Permission::create(['group' => $model->key, 'name' => 'create-' . $model->key . '-list']);
    //         Permission::create(['group' => $model->key, 'name' => 'delete-' . $model->key . '-list']);
    //         Permission::create(['group' => $model->key, 'name' => 'update-' . $model->key . '-list']);
    //         Permission::create(['group' => $model->key, 'name' => 'force-delete-' . $model->key . '-list']);

    //         $role = Role::where('name', 'Super Admin')->first();
    //         $role->givePermissionTo(Permission::where('group', $model->key)->get());
    //     });
    // }
}
