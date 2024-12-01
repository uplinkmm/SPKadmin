<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThreeDResult extends Model
{
    protected $fillable = [
        'open_datetime',
        'open_date',
        'year',
        'winning_number',
        'extra_winning_numbers'
    ];
}
