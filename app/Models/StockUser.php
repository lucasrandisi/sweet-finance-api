<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockUser extends Model
{
    use HasFactory;

    protected $table = 'stocks_users';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'stock_symbol'
    ];

    protected $attributes = [
        'amount' => 0
    ];
}
