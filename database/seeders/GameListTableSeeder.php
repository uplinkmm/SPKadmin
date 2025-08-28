<?php

namespace Database\Seeders;

use App\Models\Admin\Product;
use App\Models\Admin\GameList;
use App\Models\Admin\GameType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Admin\GameTypeProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GameListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('game_lists')->truncate();
        DB::table('game_type_product')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // $json = File::get(base_path('app/Console/Commands/data/GameList.json'));
        // $data = json_decode($json);
        // foreach ($data->ProviderGames as $obj) {
        //     $product=Product::where('code',$obj->ProductID)->first();

        //     $gameType=GameType::where('code',$obj->GameType)->first();

            // if($gameType && $product){
            //     GameList::create([
            //         'code' => $obj->GameCode,
            //         'name' => $obj->GameName,
            //         'game_type_id' => $gameType->id,
            //         'product_id' => $product->id,
            //         'image_url' => $obj->ImageUrl,
            //     ]);
            // }
        // }
        $json = File::get(base_path('app/Console/Commands/gsc/GameList.json'));
        $data = json_decode($json);
        $gameListData = [];
        $gameTypeProductData = [];

        foreach ($data->ProviderGames as $obj) {
            $product = Product::where('code', $obj->product_code)->first();
            $gameType = GameType::where('code', $obj->game_type)->first();

            if ($gameType && $product) {
                $gameListData[] = [
                    'code' => $obj->game_code,
                    'name' => $obj->game_name,
                    'game_type_id' => $gameType->id,
                    'product_id' => $product->id,
                    'image_url' => $obj->image_url,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // $gameTypeProductData[] = [
                //     'product_id' => $product->id,
                //     'game_type_id' => $gameType->id,
                //     'image' => $obj->image_url,
                //     'rate' => 1,
                //     'created_at' => now(),
                //     'updated_at' => now(),
                // ];
                 GameTypeProduct::firstOrCreate([
                    'product_id' => $product->id,
                    'game_type_id' => $gameType->id,
                  ],[
                    'product_id' => $product->id,
                    'game_type_id' => $gameType->id,
                    'image' => $obj->image_url,
                    'rate' => 1,
                  ]);
            }
        }

        // Bulk insert GameList
        if (!empty($gameListData)) {
            GameList::insert($gameListData);
        }

        // For GameTypeProduct, since you used firstOrCreate (prevent duplicates),
// we can handle it with upsert:
        // if (!empty($gameTypeProductData)) {
        //     GameTypeProduct::upsert(
        //         $gameTypeProductData,
        //         ['product_id', 'game_type_id'], // unique keys
        //         ['image', 'rate', 'updated_at'] // fields to update on duplicate
        //     );
        // }


        //correct
        // foreach ($data->ProviderGames as $obj) {
        //     $product=Product::where('code',$obj->product_code)->first();

        //     $gameType=GameType::where('code',$obj->game_type)->first();

        //     if($gameType && $product){
        //         GameList::create([
        //             'code' => $obj->game_code,
        //             'name' => $obj->game_name,
        //             'game_type_id' => $gameType->id,
        //             'product_id' => $product->id,
        //             'image_url' => $obj->image_url,
        //         ]);
                // GameTypeProduct::firstOrCreate([
                //     'product_id' => $product->id,
                //     'game_type_id' => $gameType->id,
                //   ],[
                //     'product_id' => $product->id,
                //     'game_type_id' => $gameType->id,
                //     'image' => $obj->image_url,
                //     'rate' => 1,
                //   ]);
            // }
        // }
        //end
        
    }
}
