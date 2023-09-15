<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityPostReplyLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'type',
        'selected_id',
        'user_id',
        'community_id',
        'action'
    ];
}
