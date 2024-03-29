<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $attributes = [
        'finance' => 10000,
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

	public function stocks()
	{
		return $this->belongsToMany(Stock::class, 'stocks_users')->withPivot('amount');
	}
}
