<?php

namespace App\Http\Requests\Space;

use App\Models\Space;
use App\Models\TimeSlot;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTimeSlotRequest extends FormRequest
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
        $timeslotId = $this->input('id');
        $timeSlot = TimeSlot::query()->findOrFail($timeslotId);
        $spaceid = $timeSlot->space_id;
        $space = Space::findOrFail($spaceid);
        if ($space->use_slot_rent == 1) {
            return [
                'start_time' => 'required',
                'end_time' => 'required',
                'number_of_booking' => 'required|integer',
                'time_slot_rent' => 'required|numeric',
            ];
        } else {
            return [
                'start_time' => 'required',
                'end_time' => 'required',
                'number_of_booking' => 'required|integer',
                'time_slot_rent' => 'nullable',
            ];
        }
    }

}
