<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Media;
use App\Models\Interest;
use App\Traits\MyAutiting;
 use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use App\Http\Controllers\CommenController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, CanResetPassword, Notifiable,\OwenIt\Auditing\Auditable,MyAutiting, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'full_name',
        'phone',
        'birthdate',
        'gender_type',
        'provider_name',
        'provider_id',
        'provider_access_token',
        'is_active',
        'media_id',
        'code',
        'device_token',
        'uuid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'provider_name',
        'provider_id',
        'provider_access_token',
        'updated_at',
        'created_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'id',
        'media',
        'media_id',
        'code',
        'device_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['profile_image'];

    public function getProfileImageAttribute()
    {
        if ($this->media)
            return CommenController::profileImage($this->media->thumbnail_name);
        return '';
    }

    public function getAssignedRolesAttribute()
    {
        return $this->getRoleNames();
    }

    // XXX------------RelationS------------XXX

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function media()
    {
        return $this->hasOne(Media::class, 'id', 'media_id');
    }

    public function genderType()
    {
        return $this->belongsTo(Lockup::class, 'gender_type', 'id');
    }

    public static function mprint($obj)
    {
        Log::info(print_r($obj, true));
    }

    public function interests()
    {
        return $this->hasMany(Interest::class, 'id', 'media_id');
    }
}
