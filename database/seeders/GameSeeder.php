<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $names = ['2D', '3D', 'Draw'];
        foreach ($names as $name) {
            $max = $name == '2D' ? 200000 : 30000;
            $type = match ($name) {
                '2D' => '2d',
                '3D' => '3d',
                'Draw' => 'draw',
            };
            $game = Game::firstOrCreate(
                ['name' => $name],
                [
                    'name' => $name,
                    'type' =>$type,
                ]
            );
            if ($name == '2D') {
                $time_status = ['morning', 'evening'];
                foreach ($time_status as $status) {
                    $gameSetting = $game->gameSetting()->create([
                        'name' => $name . ' ' . $status,
                        'opening_time' => $status == 'morning' ? config('2d_setting.morning_opening_time') : config('2d_setting.evening_opening_time'),
                        'closing_time' => $status == 'morning' ? config('2d_setting.morning_closing_time') : config('2d_setting.evening_closing_time'),
                        'lottery_time' => $status == 'morning' ? config('2d_setting.morning_lottery_time') : config('2d_setting.evening_lottery_time'),
                        'bet_multiplier' => $status == 'morning' ? config('2d_setting.morning_odds') : config('2d_setting.evening_odds'),
                        'closing_amount' => config('2d_setting.max_closing_bet_amount'),
                        'time_status' => $status == 'morning' ? 'morning' : 'evening',
                        'game_id' => $game->id,
                        'min' => 100,
                        'max' => $max,
                    ]);
                }
            }
            if ($name == '3D') {
                $gameSetting = $game->gameSetting()->create([
                    'name' => $name,
                    'opening_date_time' => config('3d_setting.opening_date_time'),
                    'closing_date_time' => config('3d_setting.closing_date_time'),
                    'lottery_date_time' => config('3d_setting.lottery_date_time'),
                    'bet_multiplier' => config('3d_setting.bet_multiplier'),
                    'closing_amount' => config('3d_setting.closing_amount'),
                    'time_status' => 'evening',
                    'min' => 100,
                    'max' => $max,
                ]);
            }
        }
    }
}
