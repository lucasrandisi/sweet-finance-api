<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOrder extends Model
{
    use HasFactory;
	use SoftDeletes;

	const INACTIVE_STATE = 'INACTIVE';
	const ACTIVE_STATE = 'ACTIVE';
	const COMPLETE_STATE = 'COMPLETE';

	protected $table = "stock_orders";
	public $timestamps = false;

	protected $fillable = [
		'user_id',
		'stock_symbol',
		'action',
		'amount',
		'stop',
		'limit',
		'state',
		'price_at_create_time'
	];

	protected $attributes = [
		'stop' => null
	];


	public function stock() {
		return $this->belongsTo(Stock::class);
	}

	public function user() {
		return $this->belongsTo(User::class);
	}
}
