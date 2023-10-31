<?php

namespace App\Models;

use Carbon\Carbon;
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
        'is_flagged',
        'created_at'
    ];

    public function getCreatedAtAttribute($value)
    {

        // Replace $date with your actual date
        $date = Carbon::parse($value);

        // Format the date in a human-readable way
        $formattedDate = $date->diffForHumans();

        return $formattedDate;
    }
}
