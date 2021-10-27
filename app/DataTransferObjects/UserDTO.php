<?php

namespace App\DataTransferObjects;

use App\Http\Requests\UpdateMeRequest;

class UserDTO extends DataTransferObject
{
	public string $email;
	public string $password;
	public string $name;

	public static function fromRequest(UpdateMeRequest $request){
		return new self($request->validated());
	}
}