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
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'username' => 'super-admin',
            'role' => 'super-admin',
            'password' => 'password',
            'phone_number' => '091122',
        ]);
        (new WalletService)->deposit($superAdmin, 10 * 100_000, TransactionName::CapitalDeposit);
        $admin = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'role' => 'admin',
            'password' => 'password',
            'phone_number' => '091123',
        ]);
        $permissionIds = Permission::pluck('id')->toArray();
        $admin->permissions()->sync($permissionIds);
        (new WalletService)->deposit($admin, 10 * 100_000, TransactionName::CapitalDeposit);
    }
}
