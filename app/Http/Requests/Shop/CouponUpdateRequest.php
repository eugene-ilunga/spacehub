<?php

namespace App\Http\Requests\Shop;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CouponUpdateRequest extends FormRequest
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
        $id = $this->input('id');
        return [
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:255',
                // Use the unique rule conditionally
                Rule::unique('product_coupons', 'code')->ignore($id),
            ],
            'type' => 'required',
            'value' => 'required|numeric|min:0', // Assuming value should be numeric
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'minimum_spend' => 'nullable|numeric|min:0', // If you want to enforce numeric validation
        ];
    }
}
