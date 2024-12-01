<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user=User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'password' => 'password',
            'phone_number'=>'091122',
        ]);
        $permissionIds=Permission::pluck('id')->toArray();
        $user->permissions()->sync($permissionIds);
    }
}
