<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $customer=Customer::create([
            'name'=>'Test',
            'phone_number'=>'090000',
            'password'=>bcrypt('password'),
            'otp'=>'0000',
            'is_verified'=>1,
            'verified_at'=>now(),
        ]);
    }
}
