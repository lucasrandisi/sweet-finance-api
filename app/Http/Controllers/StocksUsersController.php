<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\User;

class StocksUsersController extends Controller
{
    public function buy(User $user, Stock $stock) {
        return $stock;
    }
}
