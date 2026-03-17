<?php

namespace Database\Seeders;

use App\Models\DepositWithdrawTutorial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepositWithdrawTutorialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('deposit_withdraw_tutorials')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DepositWithdrawTutorial::firstOrCreate([
            'type'=>'deposit',
            'youtube_link'=> 'https://www.youtube.com/'
        ],[
            'title' => 'ငွေဖြည့်နည်းကြည့်ရန်',
            'type' => 'deposit',
            'youtube_link' => 'https://www.youtube.com/',
            'is_active'=>true
        ]);
        DepositWithdrawTutorial::firstOrCreate([
            'type' => 'withdraw',
            'youtube_link' => 'https://www.youtube.com/'
        ], [
            'title' => 'ငွေထုတ်နည်းကြည့်ရန်',
            'youtube_link' => 'https://www.youtube.com/',
            'type' => 'withdraw',
            'is_active' => true
        ]);
    }
}
