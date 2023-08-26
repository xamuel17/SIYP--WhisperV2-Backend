<?php

namespace App\Models\WebModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebRole extends Model
{
    use HasFactory;

    public const APP_DEVELOPER_RANK = 100;
    public const SUPER_ADMIN_RANK = 80;
    public const ADMIN_RANK = 50;
}
