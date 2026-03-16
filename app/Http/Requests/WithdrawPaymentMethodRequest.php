<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WithdrawPaymentMethodRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('withdraw_payment_methods', 'name')
            ],
            'min_limit' => 'required',
            'max_limit' => 'required',
            'status' => 'required'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $fixed_charge = $this->input('fixed_charge');
            $percentage_charge = $this->input('percentage_charge');
            $result = $fixed_charge + $percentage_charge;

            // Check if min_limit is greater than the sum of fixed_charge and percentage_charge
            if ($this->min_limit <= $result) {
                $validator->errors()->add('min_limit', __('The minimum limit must be greater than') . ' ' . $result . '.');
            }
        });
    }
}
