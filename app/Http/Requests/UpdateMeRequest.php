<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateMeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


	/**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		/** @var User $currentUser */
		$currentUser = Auth::user();

        return [
			'name' => 'sometimes|string',
			'email' => ['sometimes', 'string', Rule::unique('users')->ignore($currentUser->id)],
			'password' => 'sometimes|string|confirmed|min:6',
		];
    }
}
