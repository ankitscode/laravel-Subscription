<?php

namespace App\Models;


use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Interest extends Model
{
    use HasFactory;

    protected $casts = [
        'created_by'    => 'integer',
        'updated_by'    => 'integer',
    ];

    protected $fillable = [
       
        'user_id',
        'category_id'
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

    public function categories(){
        return $this->belongsTo(Category::class);
    }     
}
