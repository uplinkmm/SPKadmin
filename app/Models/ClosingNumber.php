<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClosingNumber extends Model
{
    protected $fillable = [
        'number',
        'amount',
        'date_time',
        'time_status',
        'game_setting_id',
        'game_id',
        'is_active',
        'created_by'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
