<?php

namespace App\Console\Commands;

use App\Models\StockOrder;
use App\Services\StockOrdersService;
use Illuminate\Console\Command;

class ExecuteOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'execute:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Get stock prices and execute orders if they match";


    public function handle(StockOrdersService $stockOrdersService)
    {
		$stockOrdersService->checkOrders();

		$this->info('Ordenes Ejecutadas');
    }
}