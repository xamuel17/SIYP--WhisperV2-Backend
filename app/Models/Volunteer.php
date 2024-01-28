<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'username',
        'session',
        'photo',
        'role',
        'status',
        'description',
        'email',
        'phone',
        'rank',
        'created_at',
        'updated_at'
    ];
}
