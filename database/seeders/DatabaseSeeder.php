<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

use Database\Seeders\GameSeeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            PermissionTableSeeder::class,
            UserSeeder::class,
            GameSeeder::class,
            CustomerSeeder::class,
            // BettingSeeder::class,
            TransactionTypeSeeder::class,
            AccountSeeder::class,
            TermAndConditionSeeder::class,
        ]);
        // (new UserSeeder())->run();
    }
}
