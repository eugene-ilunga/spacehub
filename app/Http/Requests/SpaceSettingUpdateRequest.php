<?php

namespace App\Http\Requests;

use App\Models\Membership;
use App\Models\Package;
use Illuminate\Foundation\Http\FormRequest;

class SpaceSettingUpdateRequest extends FormRequest
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
            'tax' => 'required',
            'space_units' => 'required',
            'fixed_time_slot_rental' => 'required',
            'hourly_rental' => 'required',
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'space_units.required' => 'The space units field is required',
            'fixed_time_slot_rental.required' => 'The fixed timeslot rental field is required',
            'hourly_rental.required' => 'The hourly rental field is required',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $packages = Package::all();

        // Define a function to check feature usage
        $checkFeatureUsage = function ($featureName) use ($packages) {
            $packageIds = $packages->filter(function ($package) use ($featureName) {
                $features = json_decode($package->package_feature, true);
                return !empty($features) && in_array($featureName, $features);
            })->pluck('id')->toArray();

            return Membership::whereIn('package_id', $packageIds)->exists();
        };

        $validator->after(function ($validator) use ($checkFeatureUsage) {
            // Ensure at least one rental type is enabled
            if ($this->input('fixed_time_slot_rental') == 0 && $this->input('hourly_rental') == 0) {
                $validator->errors()->add('fixed_time_slot_rental', 'At least one of the rentals must be enabled (Fixed Timeslot Rental or Hourly Rental).');
                $validator->errors()->add('hourly_rental', 'At least one of the rentals must be enabled (Fixed Timeslot Rental or Hourly Rental).');
            }

            // Check if the feature is in use in any package and return validation error
            if ($this->input('fixed_time_slot_rental') == 0 && $checkFeatureUsage('Fixed Timeslot Rental')) {
                $validator->errors()->add('fixed_time_slot_rental', 'You cannot disable Fixed Timeslot Rental feature because it is used in a package bought by a vendor.');
            }

            if ($this->input('hourly_rental') == 0 && $checkFeatureUsage('Hourly Rental')) {
                $validator->errors()->add('hourly_rental', 'You cannot disable Hourly Rental feature because it is used in a package bought by a vendor.');
            }
        });
    }
}
