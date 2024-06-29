<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCouponRequest extends FormRequest
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
          'coupon_option' => 'required',
          'uses_per_customer' => 'required',
          'coupon_rule' => 'required|integer|between:45,46',
          'amount_type' => 'required_unless:coupon_rule,46',
          'coupon_rule_data' => 'required_unless:coupon_rule,46',
          'range' => 'required_if:coupon_rule,46|array',
          'range.min' => ['required_with:range|numeric'],
          'range.max' => ['required_with:range|numeric'],
          'range.amount' => ['required_with:range|numeric'],
          'expiry_date' => 'required|date',
          'amount' => ['required_unless:coupon_rule,46'],
        ];
    }

    public function messages()
    {
        return [
          'coupon_option.required' => "Select the Way you want to generate Coupon",
          'coupon_type.required' => "Select the Type of your Coupon",
          'amount_type.required_unless' => "Select the Amount Type of your Coupon",
          'categories.required' => "Select at least one category",
          'user_type.required' => "Select at least one User Type",
          'amount.required' => "Enter the Amount",
          'expiry_date.required' => "Enter expiry date of the coupon",
          'coupon_rule.required' => "Select one Rule from Coupon Rule",
        ];
    }
}
