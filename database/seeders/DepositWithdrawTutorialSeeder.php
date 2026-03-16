<?php

namespace Database\Seeders;

use App\Models\DepositWithdrawTutorial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepositWithdrawTutorialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DepositWithdrawTutorial::firstOrCreate([
            'title'=>'‌ငွေသ္ငင်းငွေထုပ်ခြင်း',
            'youtube_link'=>'www.youtube.com'
        ],[
            'title' => '‌ငွေသ္ငင်းငွေထုပ်ခြင်း',
            'youtube_link' => 'www.youtube.com',
            'is_active'=>true
        ]);
    }
}
