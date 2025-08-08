<?php

namespace Database\Seeders;

use App\Models\Admin\GameType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('game_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $data = [
            [
                'name' => 'Slot',
                'code' => 'SLOT',
                'order' => 1,
                'status' => 1,
                'img' => 'slot.png',
            ],
            [
                'name' => 'Live Casino',
                'code' => 'LIVE_CASINO',
                'order' => 2,
                'status' => 1,
                'img' => 'live_casino.png',
            ],
            [
                'name' => 'Sport Book',
                'code' => 'SPORT_BOOK',
                'order' => 3,
                'status' => 1,
                'img' => 'sport_book.png',
            ],
            [
                'name' => 'Virtual Sport',
                'code' => 'VIRTUAL_SPORT',
                'order' => 4,
                'status' => 1,
                'img' => 'virtual_sport.png',
            ],
            [
                'name' => 'Lottery',
                'code' => 'LOTTERY',
                'order' => 5,
                'status' => 1,
                'img' => 'lottery.png',
            ],
            [
                'name' => 'Qipai',
                'code' => 'QIPAI',
                'order' => 6,
                'status' => 1,
                'img' => 'qipai.png',
            ],
            [
                'name' => 'P2P',
                'code' => 'P2P',
                'order' => 7,
                'status' => 1,
                'img' => 'p2p.png',
            ],
            [
                'name' => 'Fishing',
                'code' => 'FISHING',
                'order' => 8,
                'status' => 1,
                'img' => 'fishing.png',
            ],
            [
                'name' => 'Cock Fighting',
                'code' => 'COCK_FIGHTING',
                'order' => 9,
                'status' => 1,
                'img' => 'cock_fighting.png',
            ],
            [
                'name' => 'Bonus',
                'code' => 'BONUS',
                'order' => 10,
                'status' => 1,
                'img' => 'bonus.png',
            ],
            [
                'name' => 'ESport',
                'code' => 'ESPORT',
                'order' => 11,
                'status' => 1,
                'img' => 'esport.png',
            ],
            [
                'name' => 'Poker',
                'code' => 'POKER',
                'order' => 12,
                'status' => 1,
                'img' => 'poker.png',
            ],
            [
                'name' => 'Others',
                'code' => 'OTHERS',
                'order' => 13,
                'status' => 1,
                'img' => 'others.png',
            ],
            [
                'name' => 'Live Casino Premium',
                'code' => 'LIVE_CASINO_PREMIUM',
                'order' => 14,
                'status' => 1,
                'img' => 'live_casino_premium.png',
            ],
        ];
        // $data = [
        //     [
        //         'name' => 'Slot',
        //         'code' => '1',
        //         'order' => '1',
        //         'status' => 1,
        //         'img' => 'slots.png',
        //     ],
        //     [
        //         'name' => 'Live Casino',
        //         'code' => '2',
        //         'order' => '2',
        //         'status' => 1,
        //         'img' => 'live_casino.png',
        //     ],
        //     [
        //         'name' => 'Sport Book',
        //         'code' => '3',
        //         'order' => '3',
        //         'status' => 0,
        //         'img' => 'sportbook.png',
        //     ],
        //     [
        //         'name' => 'Fishing',
        //         'code' => '8',
        //         'order' => '4',
        //         'status' => 1,
        //         'img' => 'fishing.png',
        //     ],
        //     [
        //         'name' => 'Other',
        //         'code' => '9',
        //         'order' => '5',
        //         'status' => 0,
        //         'img' => 'other.png',
        //     ],
        //     [
        //         'name' => 'Online Casino',
        //         'code' => '11',
        //         'order' => '6',
        //         'status' => 0,
        //         'img' => 'fishing.png',
        //     ],
        //     [
        //         'name' => 'Jackpot',
        //         'code' => '12',
        //         'order' => '7',
        //         'status' => 0,
        //         'img' => 'other.png',
        //     ],
        // ];

        GameType::insert($data);
    }
}
