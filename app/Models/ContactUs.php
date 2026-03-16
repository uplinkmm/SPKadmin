<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;
    protected $fillable = [
        'facebook_link',
        'viber_number',
        'phone_number',
        'telegram_link',
        'is_active'
    ];
}
