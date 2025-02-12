<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'image',
        'status',
        'user_id'
    ];

    public function articles()
    {
        return $this->hasMany(Articles::class, 'category_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
