<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HarmSpot extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    // harmspot statuses
    public const PUBLISHED = 1;
    public const UNPUBLISHED = 0;

    // harmspot risk levels
    public const FALSE_ALARM = 0;
    public const MODERATE = 1;
    public const SEVERE = 2;
    public const CRITICAL = 3;
}
