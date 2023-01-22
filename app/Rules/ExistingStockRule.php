<?php

namespace App\Rules;

use App\Services\StocksService;
use Illuminate\Contracts\Validation\Rule;

class ExistingStockRule implements Rule
{
	private StocksService $stockService;

    public function __construct(StocksService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->stockService->getOne($value) != null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Symbol not found';
    }
}
