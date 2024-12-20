<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Community extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'purpose',
        'category',
        'photo',
        'privacy',
        'status',
        'secret_key',
        'is_flagged'
    ];

}
