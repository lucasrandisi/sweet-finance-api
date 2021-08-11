<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    public const BUY = 'BUY';
    public const SELL = 'SELL';

    protected $table = 'stock_transactions';

    protected $fillable = [
        'user_id',
        'stock_symbol',
        'amount',
        'action',
        'price'
    ];
}
