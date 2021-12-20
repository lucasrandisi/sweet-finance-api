<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\UserDTO;
use App\Http\Requests\UpdateMeRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UsersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
	private UsersService $usersService;

	public function __construct(UsersService $usersService) {
		$this->usersService = $usersService;
	}

	public function me(Request $request) {
		return new UserResource($request->user());
	}

	public function updateMe(UpdateMeRequest $request) {
		/*  @var User $currentUser */
		$currentUser = Auth::user();

		$userDto = UserDTO::fromRequest($request);

		return $this->usersService->updateUser($currentUser, $userDto);
	}
}
