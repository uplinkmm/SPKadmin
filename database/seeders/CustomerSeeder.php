<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Enums\TransactionName;
use App\Services\WalletService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        DB::beginTransaction();
        try {
            $user = User::adminUser();  // Admin or central system wallet
            $customer = Customer::create([
                'name' => 'Test',
                'phone_number' => '090000',
                'password' => bcrypt('password'),
                'otp' => '0000',
                'is_verified' => 1,
                'verified_at' => now(),
            ]);
            app(WalletService::class)->transfer($user, $customer, 50000, TransactionName::CreditTransfer);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
