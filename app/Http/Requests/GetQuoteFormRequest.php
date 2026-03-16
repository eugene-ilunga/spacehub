<?php

namespace App\Http\Requests;

use App\Models\Form;
use Illuminate\Foundation\Http\FormRequest;

class GetQuoteFormRequest extends FormRequest
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
       
        $formId = $this->input('get_quote_form_id');
        $formType = $this->input('form_type');
        // Store form type in session to use for modal display later
       
            session()->flash('formType', $formType);
        
        $form = Form::query()->find($formId);

        $inputFields = $form->input()->orderBy('order_no', 'asc')->get();

        $ruleArray = [
            'name' => 'required',
            'email_address' => 'required|email:rfc,dns'
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
        $formId = $this->input('get_quote_form_id');

        $form = Form::query()->find($formId);

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
