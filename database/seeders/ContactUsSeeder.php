<?php

namespace Database\Seeders;

use App\Models\ContactUs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */ 
    public function run(): void
    {
        //
        ContactUs::firstOrCreate([
            'facebook_link' => 'www.facebook.com',
            'viber_number'=>'09123456',
            'phone_number'=>'09123456',
            'telegram_link'=>'www.telegram.com',
        ], [
            'facebook_link' => 'www.facebook.com',
            'viber_number' => '09123456',
            'phone_number' => '09123456',
            'telegram_link' => 'www.telegram.com',
        ]);
    }
}
