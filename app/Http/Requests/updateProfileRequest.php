<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class updateProfileRequest extends FormRequest
{
    protected $stopOnFirstFailure = false;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'full_name'     => 'required|string',
            'gender_type'   => 'required|numeric',
            'city_type'     => 'required|numeric',
            'country_type'  => 'required|numeric',
            'address'       => 'required|string',
            'birthdate'     => 'required|date',
            'phone'         =>  [
                'required',
                'string',
                Rule::unique('users')->ignore(Auth::user()->id, 'id')
            ],
        ];
    }
}
