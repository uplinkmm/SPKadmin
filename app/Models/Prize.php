<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    use HasFactory;
    protected $fillable=['name','prize'];
    public function prizes_images(){
        return $this->hasMany(PrizeItemImage::class);
    }
}
