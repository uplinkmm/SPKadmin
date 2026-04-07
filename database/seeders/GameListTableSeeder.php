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
        //
         $jsonDirectory = __DIR__ . '/../../app/Console/Commands/gsc/valid-games';
        // Get all .json files inside that directory
        $files = glob($jsonDirectory . '/*.json');
        echo "Found " . count($files) . " JSON files in {$jsonDirectory}\n\n";

        if(count($files) < 0){
            echo " No games to be migrated, existing...";
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('game_lists')->truncate();
        DB::table('game_type_product')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($files as $filePath) {
            $fileName = basename($filePath);
            echo "📄 Loading file: {$fileName}\n";

            $content = file_get_contents($filePath);
            $data = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "⚠️  Failed to parse JSON in {$fileName}: " . json_last_error_msg() . "\n";
                continue;
            }

            echo "✅ Loaded " . count($data) . " provider_games from {$fileName}\n";

            $gameListData = [];
            $gameTypeProductData = [];
            foreach($data as $game){
                // echo "   First game name: " . ($game['game_name'] ?? 'N/A') . "\n";
                $product = Product::where('code', $game['product_code'])->first();
                $gameType = GameType::where('code', $game['game_type'])->first();

                if ($gameType && $product) {
                    $gameListData[] = [
                        'code' => $game['game_code'],
                        'name' => $game['game_name'],
                        'game_type_id' => $gameType->id,
                        'product_id' => $product->id,
                        'image_url' => $game['image_url'],
                        'status'=> $game['status'] == 'DEACTIVATED' ? 0 : 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $gameTypeProductData[] = [
                        'product_id' => $product->id,
                        'game_type_id' => $gameType->id,
                        'image' => $game['image_url'],
                        'rate' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($gameListData)) {
                GameList::insert($gameListData);
            }
            if (!empty($gameTypeProductData)) {
                // Deduplicate by product_id + game_type_id
                $uniqueGameTypeProducts = collect($gameTypeProductData)
                    ->unique(fn($row) => $row['product_id'] . '-' . $row['game_type_id'])
                    ->values()
                    ->toArray();

                GameTypeProduct::insertOrIgnore($uniqueGameTypeProducts);
            }

            echo "-----------------------------\n";
        }
    }
}
