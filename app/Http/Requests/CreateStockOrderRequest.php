<?php

namespace App\Http\Requests;

use App\Clients\TwelveDataClient;
use App\Models\StockTransaction;
use App\Rules\ExistingStockRule;
use App\Services\StocksService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateStockOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		$stockService = new StocksService(new TwelveDataClient());
		$existingStockRule = new ExistingStockRule($stockService);

        return [
			'stock_symbol' => ['required', $existingStockRule],
			'action' => ['required', Rule::in(StockTransaction::ACTIONS)],
			'amount' => 'required|integer|min:1',
			'limit' => 'required|numeric',
			'stop' => 'numeric|nullable'
        ];
    }
}
