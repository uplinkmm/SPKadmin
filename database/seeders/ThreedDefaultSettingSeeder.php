<?php

namespace Database\Seeders;

use App\Models\ThreedDefaultSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThreedDefaultSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ThreedDefaultSetting::firstOrCreate([
            'bet_multiplier' =>700,
            'twist_multiplier'=>10,
            'closing_amount'=>30000,
            'min'=>100,
            'max'=>10000,
        ], [
            'bet_multiplier' => 700,
            'twist_multiplier' => 10,
            'closing_amount' => 30000,  
            'min' => 100,
            'max' => 10000,
        ]);
    }
}
