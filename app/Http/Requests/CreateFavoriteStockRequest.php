<?php

namespace App\Http\Requests;

use App\Clients\TwelveDataClient;
use App\Rules\ExistingStockRule;
use App\Services\StocksService;
use Illuminate\Foundation\Http\FormRequest;

class CreateFavoriteStockRequest extends FormRequest
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
            'stock_symbol' => ['required', $existingStockRule]
        ];
    }
}
