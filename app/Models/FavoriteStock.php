<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteStock extends Model
{
	protected $table = 'favorite_stocks';

	public $timestamps = false;

	protected $fillable = [
		'user_id',
		'stock_symbol'
	];
}
