<?php

namespace App\Http\Requests;

use App\Models\Form;
use Illuminate\Foundation\Http\FormRequest;

class BookTourRequest extends FormRequest
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

        $formId = $this->input('tour_request_form_id');
        $formType = $this->input('form_type');
        // Store form type in session only when there are validation errors
        
            session()->flash('formType', $formType);
        
        $form = Form::query()->find($formId);
        // Check if the form is null and return an empty array if not found
        if (is_null($form)) {
            return [];
        }

        $inputFields = $form->input()->orderBy('order_no', 'asc')->get();

        $ruleArray = [
            'user_name' => 'required',
            'user_email_address' => 'required|email:rfc,dns'
        ];

        foreach ($inputFields as $inputField) {
            if ($inputField->is_required == 1) {
                if ($inputField->type == 8) {
                    $ruleArray['form_builder_' . $inputField->name] = 'required';
                } else {
                    $ruleArray[$inputField->name] = 'required';
                }
            }

            if (($inputField->type == 8) && $this->hasFile('form_builder_' . $inputField->name)) {
                $file = $this->file('form_builder_' . $inputField->name);
                $fileExtension = $file->getClientOriginalExtension();

                $maxSize = intval($inputField->file_size);
                // convert mb to kb
                $convertedSize = $maxSize * 1024;

                $ruleArray['form_builder_' . $inputField->name] = [
                    function ($attribute, $value, $fail) use ($fileExtension) {
                        if (strcmp('zip', $fileExtension) != 0) {
                            $fail('Only .zip file is allowed.');
                        }
                    },
                    'max:' . $convertedSize
                ];
            }
        }

        return $ruleArray;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $formId = $this->input('tour_request_form_id');

        $form = Form::query()->find($formId);
        if (is_null($form)) {
            return [];
        }

        $inputFields = $form->input()->orderBy('order_no', 'asc')->get();

        $messageArray = [];

        foreach ($inputFields as $inputField) {
            if ($inputField->is_required == 1) {
                if ($inputField->type == 8) {
                    $messageArray['form_builder_' . $inputField->name . '.required'] = 'The ' . strtolower($inputField->label) . ' field is required.';
                } else {
                    $ruleArray[$inputField->name] = 'required';
                }
            }

            if (($inputField->type == 8) && $this->hasFile('form_builder_' . $inputField->name)) {
                $maxSize = intval($inputField->file_size);

                $messageArray['form_builder_' . $inputField->name . '.max'] = 'The file must not be greater than ' . $maxSize . ' megabytes.';

                $messageArray['form_builder_' . $inputField->name . '.required'] = 'The ' . strtolower($inputField->label) . ' is required.';
            }
        }
        return $messageArray;
    }
}
