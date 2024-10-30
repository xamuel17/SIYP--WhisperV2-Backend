<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'alert_control',
        'geo_fencing',
        'audio_recording',
        'panic_alert',
        'show_notification',
        'timer',
        'theme'
    ];
}
