<?php

namespace Database\Seeders;

use App\Models\Admin\Product;
use App\Models\Admin\GameType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Models\Admin\GameTypeProduct;
use Illuminate\Support\Facades\Config;

class GameTypeProductTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('game_type_product')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');


    //     $data = [
    //       [
    //         'product_id' => 1046,
    //         'game_type_id' => 3,
    //         'image' => 'pragmatic_play.jpeg',
    //         'rate' => '1000.0000',
    //       ],
    //       [
    //         'product_id' => 1006,
    //         'game_type_id' => 1,
    //         'image' => 'pragmatic_play.jpeg',
    //         'rate' => '1.0000',
    //       ],

    //       [
    //         'product_id' => 1009,
    //         'game_type_id' => 1,
    //         'image' => 'cq_9.jpeg',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1011,
    //         'game_type_id' => 1,
    //         'image' => 'play_pearls.jpeg',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1013,
    //         'game_type_id' => 1,
    //         'image' => 'joker.jpeg',
    //         'rate' => '1000.0000',
    //       ],
    //       [
    //         'product_id' => 1014,
    //         'game_type_id' => 1,
    //         'image' => 'pragmatic_play.jpeg',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1039,
    //         'game_type_id' => 1,
    //         'image' => 'pragmatic_play.jpeg',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1041,
    //         'game_type_id' => 1,
    //         'image' => 'habanero.jpeg',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1048,
    //         'game_type_id' => 1,
    //         'image' => 'pragmatic_play.jpeg',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1049,
    //         'game_type_id' => 1,
    //         'image' => 'evoplay.png',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1050,
    //         'game_type_id' => 1,
    //         'image' => 'playstar.jpeg',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1075,
    //         'game_type_id' => 1,
    //         'image' => 'pragmatic_play.jpeg',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1077,
    //         'game_type_id' => 1,
    //         'image' => 'skywind_group.png',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1084,
    //         'game_type_id' => 1,
    //         'image' => 'advant_play.png',
    //         'rate' => '1.0000',
    //       ],
    //         // [
    // //   'product_id' => 1085,
    // //   'game_type_id' =>1 ,
    // //   'image' => 'j_d_b.png',
    // //   'rate' => '1.0000',
    // // ],
    //       [
    //         'product_id' => 1091,
    //         'game_type_id' => 1,
    //         'image' => 'jili.png',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1002,
    //         'game_type_id' => 2,
    //         'image' => 'evolution_gaming.webp',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1003,
    //         'game_type_id' => 2,
    //         'image' => 'yee_bet.png',
    //         'rate' => '1000.0000',
    //       ],
    //         // [
    // //   'product_id' => 1004,
    // //   'game_type_id' =>2 ,
    // //   'image' => 'big_gaming.jpeg',
    // //   'rate' => '1000.0000',
    // // ],
    //       [
    //         'product_id' => 1005,
    //         'game_type_id' => 2,
    //         'image' => 'spade_gaming.jpeg',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1011,
    //         'game_type_id' => 2,
    //         'image' => 'play_pearls.jpeg',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1022,
    //         'game_type_id' => 2,
    //         'image' => 'sexy_gaming.png',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1052,
    //         'game_type_id' => 2,
    //         'image' => 'dream_gaming.png',
    //         'rate' => '1000.0000',
    //       ],
    //       [
    //         'product_id' => 1077,
    //         'game_type_id' => 2,
    //         'image' => 'skywind_group.png',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1009,
    //         'game_type_id' => 8,
    //         'image' => 'cq_9.jpeg',
    //         'rate' => '1.0000',
    //       ],
    //         // [
    // //   'product_id' => 1091,
    // //   'game_type_id' =>8 ,
    // //   'image' => 'jili.png',
    // //   'rate' => '1.0000',
    // // ], 
    //       [
    //         'product_id' => 1017,
    //         'game_type_id' => 12,
    //         'image' => 'funta_gaming.jpg',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1006,
    //         'game_type_id' => 2,
    //         'image' => 'pragmatic_play.jpeg',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1006,
    //         'game_type_id' => 11,
    //         'image' => 'pragmatic_play.jpeg',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1006,
    //         'game_type_id' => 12,
    //         'image' => 'pragmatic_play.jpeg',
    //         'rate' => '1.0000',
    //       ],
    //       [
    //         'product_id' => 1006,
    //         'game_type_id' => 8,
    //         'image' => 'pragmatic_play.jpeg',
    //         'rate' => '1.0000',
    //       ],
    //     ];
    //     foreach ($data as $obj) {
    //       $product = Product::where('code', $obj['product_id'])->first();

    //       $gameType = GameType::where('code', $obj['game_type_id'])->first();
    //       if ($gameType && $product) {
    //         GameTypeProduct::create([
    //   'product_id' => $product->id,
    //   'game_type_id' => $gameType->id,
    //   'image' => $obj['image'],
    //   'rate' => $obj['rate'],
    // ]);
    //       }

    //     }

    $operatorCode = Config::get('game.api.operator_code');
    $secretKey = Config::get('game.api.secret_key');
    $apiUrl = Config::get('game.api.url') . '/api/operators/available-products';
    $password = Config::get('game.api.password');
    // Generate the signature
    $requestTime = now()->format('YmdHis');
    $signature = md5($requestTime . $secretKey . 'productlist' . $operatorCode);
    // Prepare the payload    
    $data = [
      'operator_code' => $operatorCode,
      'sign' => $signature,
      'request_time' => $requestTime,
    ];
    $response = Http::withHeaders([
      // 'Content-Type' => 'application/json',
      'Accept' => 'application/json',
    ])->get($apiUrl, $data);
    $availableProudcts = $response->json();
    foreach ($availableProudcts as $av_product) {
      $product = Product::where('code', $av_product['product_code'])->first();
      $gameType = GameType::where('code', $av_product['game_type'])->first();
      if ($gameType && $product) {
        $gameTypeProduct = GameTypeProduct::firstOrCreate([
          'product_id' => $product->id,
          'game_type_id' => $gameType->id,
        ], [
          'product_id' => $product->id,
          'game_type_id' => $gameType->id,
          'image' => 'defualt',
          'rate' => 1,
        ]);
      }
    }
  }
}
