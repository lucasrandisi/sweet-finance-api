<?php

namespace App\Console\Commands;

use App\Services\StocksService;
use App\Services\TwelveDataService;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'execute:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(TwelveDataService $twelveDataService)
    {
        for ($i=0; $i<=10; $i++) {
			$response = $twelveDataService->getData('price', ['symbol' => 'IBM']);
			$this->info($response);
		}
    }
}
