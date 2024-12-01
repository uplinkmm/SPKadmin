<?php

namespace App\Console\Commands;

use App\Actions\TwoD\FetchHistoricalThaiStockTwoDAction;
use Illuminate\Console\Command;

class FetchHistoricalThaiStockTwoDCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-historical-thai-stock-two-d-command';

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
        (new FetchHistoricalThaiStockTwoDAction())->run(CurrentDate());
    }
}
