<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreedDefaultSetting extends Model
{
    use HasFactory;
    protected $fillable = ['bet_multiplier','twist_multiplier','closing_amount','min','max'];
}
