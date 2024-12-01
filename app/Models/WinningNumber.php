<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WinningNumber extends Model
{
    use HasFactory;
    protected $fillable=['two_d','modern','internet','tw','date_time','lottery_time','set','value'];
    
}
