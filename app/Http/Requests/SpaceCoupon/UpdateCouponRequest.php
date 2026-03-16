<?php

namespace App\Http\Requests\SpaceCoupon;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
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
        // $id = $this->route('id'); // Get the coupon ID from the route
        $id = $this->input('id'); // Get the coupon ID from the request data

        return [
            'space_id' => 'nullable|exists:spaces,id',
            'seller_id' => 'nullable|exists:sellers,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:space_coupons,code,' . $id,
            'space_type' => 'required',
            'coupon_type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'serial_number' => 'required|integer',
            'spaces' => 'required|array',
            'spaces.*' => 'exists:spaces,id',
        ];
    }
}
