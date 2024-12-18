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
            //slot
            GameTypeTableSeeder::class,
            ProductTableSeeder::class,
            GameTypeProductTableSeeder::class,
            // gameseeder
            // AsiaGamingTablesSeeder::class,
            // CQ9GameListTableSeeder::class,
            // CQ9FishingTablesSeeder::class,
            // EvolutionGamingTableSeeder::class,
            // PragmaticPlaySeeder::class,
            // PGSoftGameListSeeder::class,
            // JokerGameListSeeder::class,
            // SexyGamingSeeder::class,
            // RealTimeGamingSeeder::class,
            // YggdrasilSeeder::class,
            // JDBTablesSeeder::class,
            // KAGamingTablesSeeder::class,
            // SpadeGamingTablesSeeder::class,
            // SpadeGamingFishingTablesSeeder::class,
            // PlayStarTablesSeeder::class,
            // PlayStarFishingTablesSeeder::class,
            // HabaneroGamingTablesSeeder::class,
            // MrSlottyTablesSeeder::class,
            // TrueLabTablesSeeder::class,
            // BGamingTablesSeeder::class,
            // WazdanTablesSeeder::class,
            // FaziTablesSeeder::class,
            // NetGameTablesSeeder::class,
            // RedRakeTablesSeeder::class,
            // BoongoTablesSeeder::class,
            // SkywindTablesSeeder::class,
            // SkywindCasinoTablesSeeder::class,
            // AdvantPlayTablesSeeder::class,
            // GENESISTablesSeeder::class,
            // SimplePlayTablesSeeder::class,
            // JiliTablesSeeder::class,
            // FuntaGamingTablesSeeder::class,
            // FelixGamingTablesSeeder::class,
            // SmartSoftTablesSeeder::class,
            // ZeusPlayTablesSeeder::class,
            // NetentTablesSeeder::class,
            // RedTigerTablesSeeder::class,
            // GamingWorldTablesSeeder::class,
            // YesGetRichTablesSeeder::class,
            // Live22SMTablesSeeder::class,
            //end
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
