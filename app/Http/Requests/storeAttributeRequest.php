<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class storeAttributeRequest extends FormRequest
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

        return [
            'label_name' => 'required|string|unique:attributes,label_name',
            'field_type' => 'required|numeric',
            'field_required' => 'required|numeric',
            'field_data.*' => ['required','required_if:field_type,Dropdown'],
        ];


    }
    public function messages()
    {
        return [
            'label_name.required' => 'Default Label Name is Required',
            'field_data.*.required' => 'Attribute Value must not be empty',
        ];
    }

}
