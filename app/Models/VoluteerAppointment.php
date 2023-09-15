<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoluteerAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'volunteer_id',
        'appointment_type',
        'appointment_date',
        'appointment_time',
        'notes',
        'status',
        'created_at',
        'updated_at'
    ];
}
