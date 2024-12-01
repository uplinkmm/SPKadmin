<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwoDResult extends Model
{
    protected $fillable = [
        'history_id',
        'stock_datetime',
        'stock_date',
        'open_time',
        'day_part',
        'set',
        'value',
        'twod'
    ];

}
