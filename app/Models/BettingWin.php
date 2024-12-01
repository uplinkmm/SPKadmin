<?php

namespace App\Models;

use App\Models\GameSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BettingWin extends Model
{
    use HasFactory;
    protected $fillable = [
        'number',
        'date_time',
        'time_status',
        'is_approved',
        'created_by',
        'approved_by',
        'game_setting_id',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function game_setting()
    {
        return $this->belongsTo(GameSetting::class);
    }

    public function twistWinNumbers()
    {
        return $this->hasMany(TwistWinNumber::class);
    }
}
