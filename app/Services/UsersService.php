<?php

namespace App\Services;

use App\DataTransferObjects\UserDTO;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UsersService
{
	public function updateUser(User $user, UserDTO $userNewData) {
		$user->fill((array) $userNewData);
		$user->save();

		return $user;
	}
}