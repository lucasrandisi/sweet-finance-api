<?php

namespace App\Console\Commands;

use App\Clients\TwelveDataClient;
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
     * @return void
     */
    public function handle(TwelveDataClient $twelveDataClient)
    {
        for ($i=0; $i<=10; $i++) {
			$response = $twelveDataClient->getData('price', ['symbol' => 'IBM']);
			$this->info($response);
		}
    }
}
