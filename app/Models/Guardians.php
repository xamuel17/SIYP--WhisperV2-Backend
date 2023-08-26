<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardians extends Model
{
    use HasFactory;

  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $fillable = [
        'ward_id',
        'ward_username',
        'guardian_id',
        'guardian_username',
        'status',

    ];
}
