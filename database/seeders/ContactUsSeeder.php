<?php

namespace Database\Seeders;

use App\Models\ContactUs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */ 
    public function run(): void
    {
        //
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('contact_us')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        ContactUs::firstOrCreate([
            'facebook_link' => 'https://www.facebook.com/',
            'viber_number'=>'09123456',
            'phone_number'=>'09123456',
            'telegram_link'=> 'http://web.telegram.org/',
        ], [
            'facebook_link' => 'https://www.facebook.com/',
            'viber_number' => '09123456',
            'phone_number' => '09123456',
            'telegram_link' => 'http://web.telegram.org/',
        ]);
    }
}
