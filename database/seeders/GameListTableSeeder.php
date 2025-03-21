<?php

namespace Database\Seeders;

use App\Models\Admin\Product;
use App\Models\Admin\GameList;
use App\Models\Admin\GameType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GameListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('game_lists')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $json = File::get(base_path('app/Console/Commands/data/GameList.json'));
        $data = json_decode($json);
        foreach ($data->ProviderGames as $obj) {
            $product=Product::where('code',$obj->ProductID)->first();

            $gameType=GameType::where('code',$obj->GameType)->first();

            if($gameType && $product){
                GameList::create([
                    'code' => $obj->GameCode,
                    'name' => $obj->GameName,
                    'game_type_id' => $gameType->id,
                    'product_id' => $product->id,
                    'image_url' => $obj->ImageUrl,
                ]);
            }
        }
    }
}
