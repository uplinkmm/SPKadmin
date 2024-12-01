<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Schema::disableForeignKeyConstraints();
        DB::table('bettings')->truncate();
        DB::table('betting_numbers')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::beginTransaction();
        try {
            // $total_amount=[2000, 3000, 4000, 5000];
            // $data = [];
            // for ($i = 1; $i <= 100; $i++) {
            //     $data[] = [
            //         'betting_no_id' => 'BET' . str_pad($i, 10, '0', STR_PAD_LEFT),
            //         'date_time' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d H:i:s'),
            //         'total_amount' => $total_amount[array_rand($total_amount)],
            //         'time_status' => rand(0, 1) ? 'morning' : 'evening',
            //         'customer_id' => 1,
            //         'game_id' => 1,
            //         'created_at' => Carbon::now(),
            //         'updated_at' => Carbon::now(),
            //     ];
            // }
            // DB::table('bettings')->insert($data);

            $total_amount_options = [2000, 3000, 4000, 5000];
            $betting_amount_options = [300, 400, 500, 1000];
            $global_betting_no_counter = 1; // Initialize a counter for unique betting_no_id

            $gameIds = [1, 2];
            foreach ($gameIds as $gameId) {
                $betting_data = [];
                $betting_number_data = [];

                for ($i = 1; $i <= 200; $i++) {
                    $gameId= $i>100 ? 2 : 1;
                    // if ($i > 100) {
                    //     $gameId = 2;
                    // }
                    $total_amount = $total_amount_options[array_rand($total_amount_options)];
                    $time_status = rand(0, 1) ? 'morning' : 'evening';
                    $game_setting_id = $gameId == 1 ? (($time_status === 'morning') ? 1 : 2) : 3;
                    // $betting_no_id = 'BET' . str_pad($gameId.'_'.$betting_no_counter++, 10, '0', STR_PAD_LEFT); // Unique betting_no_id
                    $betting_no_id = 'BET' . str_pad($global_betting_no_counter++, 10, '0', STR_PAD_LEFT);

                    // $betting_id=$global_betting_no_counter++;
                    // $betting_no_id = 'BET' . str_pad($gameId, 2, '0', STR_PAD_LEFT) . str_pad($betting_no_counter++, 8, '0', STR_PAD_LEFT);

                    $betting_data[] = [
                        'betting_no_id' => $betting_no_id,
                        'date_time' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d H:i:s'),
                        'total_amount' => $total_amount,
                        'time_status' => $time_status,
                        'customer_id' => 1,
                        'game_setting_id'=>$game_setting_id,
                        'game_id' => $gameId,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];

                    $remaining_amount = $total_amount;
                    $num_betting_numbers = rand(1, 4);

                    // Distribute amounts ensuring the total equals the total_amount
                    for ($j = 0; $j < $num_betting_numbers; $j++) {
                        if ($j == $num_betting_numbers - 1) {
                            // Last iteration, assign remaining amount
                            $amount = $remaining_amount;
                        } else {
                            // Assign a random amount, ensuring it doesn't exceed remaining_amount
                            $amount = $betting_amount_options[array_rand($betting_amount_options)];
                            if ($amount > $remaining_amount) {
                                $amount = $remaining_amount;
                            }
                            $remaining_amount -= $amount;
                        }
                        $number = $gameId == 2
                        ? str_pad(strval(rand(0, 999)), 3, '0', STR_PAD_LEFT)
                        : str_pad(strval(rand(0, 99)), 2, '0', STR_PAD_LEFT);
                        $betting_number_data[] = [
                            'number' => $number,
                            'amount' => $amount,
                            'betting_multiplier' => $gameId == 1 ? 85 : 700,
                            // 'game_setting_id' => $game_setting_id,
                            'betting_id' => $i, // Assuming `betting_id` starts from 1 and increments
                            'is_win' => 0,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                    }
                }
                DB::table('bettings')->insert($betting_data);
                DB::table('betting_numbers')->insert($betting_number_data);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}
