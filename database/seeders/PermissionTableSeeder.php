<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $permissions=[
            'Transaction',
            '2d',
            '3d',
            'Setting',
            'Slot',
        ];
        foreach ($permissions as $permission) {
            Permission::create([
                'name' => Str::title($permission),
                'slug' => Str::slug($permission, '-')
            ]);
        }
    }
}
