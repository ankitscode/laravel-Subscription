<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeProductRequest extends FormRequest
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
            // 'attributeSet' => 'required',
            // 'name[1]' => 'sometime|required',
        ];
    }

    public function messages()
    {
        return [
            'attributeSet.required' => 'Product must have some name',
            'name[1].required' => 'Product must have some name',
        ];
    }
}
