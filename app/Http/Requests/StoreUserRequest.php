<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    protected $stopOnFirstFailure = false;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'full_name' => 'required|string',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'required|numeric|unique:users,phone',
            'birthdate' => 'required|date',
            'gender_type'   => 'required|numeric',
            'city_type'     => 'required|numeric',
            'country_type'  => 'required|numeric',
            'address'       => 'required|string',
            'user_type' => 'required|numeric',
            'image'     => 'image|mimes:jpeg,jpg,png|max:2048',
            'password'  => 'required|string|min:8',
            'password_confirmation' => 'required_with:password|same:password|min:8'
        ];
    }
}
