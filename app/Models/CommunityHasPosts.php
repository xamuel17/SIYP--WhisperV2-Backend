<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommunityHasPosts extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'community_id',
        'title',
        'content',
        'status',
        'photos',
        'likes',
        'dislikes',
        'is_flagged',
        'created_at'
    ];
}
