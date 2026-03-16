<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpaceSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Authorize the request (true if authorization is not needed)
        return true;
    }

    public function rules()
    {
        $rules = [];

        // Conditionally apply validation rules based on input request values
        if ($this->has('fixed_time_slot_rental')) {
            $rules['fixed_time_slot_rental'] = 'required';
        }

        if ($this->has('hourly_rental')) {
            $rules['hourly_rental'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        // Conditionally set messages
        if ($this->has('fixed_time_slot_rental')) {
            $messages['fixed_time_slot_rental.required'] = 'The fixed timeslot rental field is required';
        }

        if ($this->has('hourly_rental')) {
            $messages['hourly_rental.required'] = 'The hourly rental field is required';
        }

        return $messages;
    }

    // Custom validation logic to ensure at least one of the rentals is enabled
    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('fixed_time_slot_rental') && $this->has('hourly_rental')) {
                if ($this->input('fixed_time_slot_rental') == 0 && $this->input('hourly_rental') == 0) {
                    $validator->errors()->add('fixed_time_slot_rental', 'At least one of the rentals must be enabled (Fixed Timeslot Rental or Hourly Rental).');
                    $validator->errors()->add('hourly_rental', 'At least one of the rentals must be enabled (Fixed Timeslot Rental or Hourly Rental).');
                }
            } elseif ($this->has('fixed_time_slot_rental') && $this->input('fixed_time_slot_rental') == 0) {
                $validator->errors()->add('fixed_time_slot_rental', 'you can not disable');
            } elseif ($this->has('hourly_rental') && $this->input('hourly_rental') == 0) {
                $validator->errors()->add('hourly_rental', 'you can not disable');
            }
        });
    }

}
