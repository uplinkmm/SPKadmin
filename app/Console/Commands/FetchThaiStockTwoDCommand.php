<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use App\Actions\LiveData\FetchThaiStockTwoDAction;

class FetchThaiStockTwoDCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-thai-stock-two-d-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        if((new FetchThaiStockTwoDAction())->run()){
            $str = 'Fetched at : ' . CurrentTime();
        }
        else{
            $str = 'Fetched error at : ' . CurrentTime();
        }
        Log::info($str);
    }
}
