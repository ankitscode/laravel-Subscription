<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateAttributeRequest extends FormRequest
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
            'label_name' => 'required|string',
            'field_type' => 'required',
            'field_required' => 'required|boolean',
            'field_data.*' => ['required','required_if:field_type,4'],
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
