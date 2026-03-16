<?php

namespace App\Http\Requests\SpaceCoupon;

use App\Http\Helpers\SellerPermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'space_id' => 'nullable|exists:spaces,id',
            'seller_id' => 'nullable|exists:sellers,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:space_coupons,code',
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

    protected function withValidator($validator)
    {
        // Perform the membership check before validation
        $vendor = Auth::guard('seller')->user();

        if ($vendor) {
            $vendor_id = $vendor->id;

            if ($vendor_id != 0) {
                $hasMembership = SellerPermissionHelper::currentPackagePermission($vendor_id);

                if ($hasMembership == null) {
                    // Vendor does not have an active membership
                    $language = getVendorLanguage();
                    Session::flash('warning', __('It appears that you currently do not have a membership') . '. ' . __('Please consider purchasing a plan to enjoy our services') . '.');

                    throw new ValidationException($validator, response()->json([
                        'status' => 'no_membership',
                        'redirect_url' => route('vendor.plan.extend.index', ['language' => $language->code]),
                    ], 200));
                }
            }
        }
    }
}
