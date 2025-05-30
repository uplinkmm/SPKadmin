<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Actions\LiveData\SaveModernInternetTwoDAction;

class SaveThaiStockModernInternetDailyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-thai-stock-modern-internet-daily-command';

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
        (new SaveModernInternetTwoDAction())->run();
    }
}
