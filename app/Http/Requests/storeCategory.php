<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeCategory extends FormRequest
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
            'category_name.en' => 'required|string|unique:categories,name',
            'category_name.ar' => 'nullable|string|unique:categories,name',
        ];
    }

    public function messages()
    {
        return [
            'category_name.en.required' => 'Category Name is Required in English',
            'category_name.ar.string' => 'Category Name is Required in Arabic',
        ];
    }
}
