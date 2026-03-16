<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositWithdrawTutorial extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'youtube_link',
        'is_active'
    ];
}
