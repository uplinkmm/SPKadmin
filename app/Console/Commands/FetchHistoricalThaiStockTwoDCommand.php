<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Actions\LiveData\FetchHistoricalThaiStockTwoDAction;

class FetchHistoricalThaiStockTwoDCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-historical-thai-stock-two-d-command {startdate?} {enddate?}';

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
        $startdate = $this->argument('startdate');
        $enddate = $this->argument('enddate');
        if(!$startdate && !$enddate){
            (new FetchHistoricalThaiStockTwoDAction())->run(CurrentDate());
        }
        if($startdate && $enddate){
            (new FetchHistoricalThaiStockTwoDAction())->runWithStartEndDates($startdate,$enddate);
        }
    }
}
