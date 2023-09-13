<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommunityCommentHasReply extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'community_comment_id',
        'content',
        'photos',
        'likes',
        'dislikes',
        'is_flagged'
    ];
}
