<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Permission;
use App\Enums\TransactionName;
use App\Services\WalletService;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user=User::create([
            'name' => 'Super Admin',
            'username' => 'super-admin',
            'role'=>'super-admin',
            'password' => 'password',
            'phone_number'=>'091122',
        ]);
        $permissionIds=Permission::pluck('id')->toArray();
        $user->permissions()->sync($permissionIds);
        (new WalletService)->deposit($user, 10 * 100_000, TransactionName::CapitalDeposit);
    }
}
