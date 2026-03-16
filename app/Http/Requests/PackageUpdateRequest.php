<?php

namespace App\Http\Requests;

use App\Models\BasicSettings\Basic;
use Illuminate\Foundation\Http\FormRequest;

class PackageUpdateRequest extends FormRequest
{
    protected $setting;
    
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
     * Prepare the data for validation.
     *
     * Fetch the setting from the database before validation.
     */
    protected function prepareForValidation()
    {
        $this->setting = Basic::select('hourly_rental', 'fixed_time_slot_rental', 'multi_day_rental')->first();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'price' => 'required|numeric',
            'icon' => 'required',
            'term' => 'required',
            'number_of_space' => 'required|numeric',
            'number_of_service_per_space' => 'required|numeric',
            'number_of_option_per_service' => 'required|numeric',
            'number_of_slider_image_per_space' => 'required|numeric',
            'number_of_amenities_per_space' => 'required|numeric',
            'status' => 'required',

            // Validate features array and ensure at least one of the required values is selected
            'features' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $requiredFeatures = ['Fixed Timeslot Rental', 'Hourly Rental', 'Multi Day Rental'];

                    // Check if both are required (both set to 1)
                    if ($this->setting->hourly_rental == 1 && $this->setting->fixed_time_slot_rental == 1 && $this->setting->multi_day_rental == 1) {
                        if (empty($value) || !array_intersect($requiredFeatures, $value)) {
                            $fail(__('Please select at least one feature among "Fixed Timeslot Rental" and "Hourly Rental" and "Multi Day Rental"') . '.');
                        }
                    }

                    // Check if only 'Hourly Rental' is required
                    elseif ($this->setting->hourly_rental == 1) {
                        if (empty($value) || !in_array('Hourly Rental', $value)) {
                            $fail(__('The "Hourly Rental" feature is required') . '.');
                        }
                    }

                    // Check if only 'Fixed Timeslot Rental' is required
                    elseif ($this->setting->fixed_time_slot_rental == 1) {
                        if (empty($value) || !in_array('Fixed Timeslot Rental', $value)) {
                            $fail(__('The "Fixed Timeslot Rental" feature is required') . '.');
                        }
                    }
                    // Check if only 'Multi Day Rental' is required
                    elseif ($this->setting->multi_day_rental == 1) {
                        if (empty($value) || !in_array('Multi Day Rental', $value)) {
                            $fail(__('The "Multi Day Rental" feature is required') . '.');
                        }
                    }
                },
            ],
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        // Apply the condition for the 'features.required' message
        if ($this->setting->hourly_rental == 1 && $this->setting->fixed_time_slot_rental == 1 && $this->setting->multi_day_rental == 1) {
            $messages['features.required'] = __('Please select at least one feature among "Fixed Timeslot Rental" and "Hourly Rental" and "Multi Day Rental"') . '.';
        } elseif ($this->setting->hourly_rental == 1) {
            $messages['features.required'] = __('The "Hourly Rental" feature is required') . '.';
        } elseif ($this->setting->fixed_time_slot_rental == 1) {
            $messages['features.required'] = __('The "Fixed Timeslot Rental" feature is required') . '.';
        } elseif ($this->setting->multi_day_rental == 1) {
            $messages['features.required'] = __('The "Multi Day Rental" feature is required') . '.';
        }

        return $messages;
    }

    /**
     * Handle failed validation.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // Return the validation errors as a JSON response
        throw new \Illuminate\Validation\ValidationException($validator, response()->json(['errors' => $validator->errors()], 400));
    }
}
