<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoluteerAvailableDays extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'volunteer_id',
        'days_of_week',
        'date',
        'start_time',
        'end_time',
        'created_at',
        'updated_at'
    ];
}
