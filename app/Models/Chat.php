<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        '_id',
        'volunteer_id',
        'user_id',
        'text',
        'image',
        'sent',
        'received',
        'started',
        'chat_id',
        'appointment_id'

    ];
}
