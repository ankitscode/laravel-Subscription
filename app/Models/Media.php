<?php

namespace App\Models;

use App\Traits\MyAutiting;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use MyAutiting;

    protected $casts = [
        'created_by'    => 'integer',
        'updated_by'    => 'integer',
    ];

    protected $fillable = [
        'type',
        'file_size',
        'name',
        'thumbnail_name',
        'updated_by',
    ];

    protected $hidden = [
        'created_at',
        'created_by',
        'updated_by',
        'updated_at',
        'deleted_at',
    ];

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
