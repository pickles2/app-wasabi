<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Account;
use App\Rules\ReservedAccount;

class StoreUserSubEmail extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		// 認可は別の箇所で行うので、ここでは素通りさせる
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'email' => 'required|string|email|max:255|unique:user_sub_emails,email',
		];
	}
}
