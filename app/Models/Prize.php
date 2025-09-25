<?php

namespace App\Models;

use App\Models\GameSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prize extends Model
{
    use HasFactory;
    protected $fillable=['name','prize','game_setting_id'];
    public function prizes_images(){
        return $this->hasMany(PrizeItemImage::class);
    }
    public function game_setting(){
        return $this->belongsTo(GameSetting::class);
    }
}
