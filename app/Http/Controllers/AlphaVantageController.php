<?php

namespace App\Http\Controllers;


use App\Services\AlphaVantageService;
use Illuminate\Http\Request;

class AlphaVantageController extends Controller
{
    private AlphaVantageService $alphaVantageService;

    public function __construct(
        AlphaVantageService $alphaVantageService
    ) {
        $this->alphaVantageService = $alphaVantageService;
    }

    public function __invoke(Request $request) {
        $data = $this->alphaVantageService->query($request->query());

        return response()->json([$data]);
    }
}
