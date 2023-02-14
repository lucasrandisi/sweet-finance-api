<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockResource;
use App\Services\StocksService;
use Illuminate\Http\Request;

class StocksController extends Controller
{
    public function __construct(private StocksService $stocksService) {
    }

    public function index(Request $request) {
        $filters = $request->query('filters', []);
        $limit = $request->query('limit', 100);

        $stocks = $this->stocksService->getAll($limit, $filters);

        return StockResource::collection($stocks);
    }
}
