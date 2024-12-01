<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TwistWinNumber extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'number',
        'betting_win_id',
        'created_by'
    ];

    public function bettingWin()
    {
        return $this->belongsTo(BettingWin::class);
    }
}
