<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOrder extends Model
{
    use HasFactory;
	use SoftDeletes;

	protected $table = "stock_orders";
	public $timestamps = false;

	public function stock() {
		return $this->belongsTo(Stock::class);
	}

	public function user() {
		return $this->belongsTo(User::class);
	}
}
