<?php

namespace App\Http\Requests\Page;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
   * @return array
   */
  public function rules()
  {
    $ruleArray = [
      'status' => 'required'
    ];
    $langForValidation  = Language::query()->where('is_default', '=', 1)->firstOrFail();
    $defaultLanguageCode = $langForValidation->code;

    $languages = Language::all();

    foreach ($languages as $language) {
      if ($language->code === $defaultLanguageCode) {
        $ruleArray[$language->code . '_title'] = 'required|max:255|unique:page_contents,title';
        $ruleArray[$language->code . '_content'] = 'min:15';
      } else {
        // For other languages, check if any field is filled
        if (
          $this->filled($language->code . '_title') ||
          $this->filled($language->code . '_meta_keyword') ||
          $this->filled($language->code . '_meta_description') ||
          $this->filled($language->code . '_content')
        ) {
          $ruleArray[$language->code . '_title'] = 'required|max:255|unique:page_contents,title';
          $ruleArray[$language->code . '_content'] = 'min:15';
        }
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
    $messageArray = [];

    $languages = Language::all();

    foreach ($languages as $language) {
      $messageArray[$language->code . '_title.required'] = __('The title field is required for'). ' ' . $language->name . ' ' .__('language') . '.';

      $messageArray[$language->code . '_title.max'] = __('The title field cannot contain more than 255 characters for') . ' ' . $language->name . ' '. __('language') . '.';

      $messageArray[$language->code . '_title.unique'] = __('The title field must be unique for') . ' ' . $language->name . ' ' . __('language') . '.';

      $messageArray[$language->code . '_content.min'] = __('The content field at least have 15 characters for') . ' ' . $language->name . ' ' . __('language') . '.';
    }

    return $messageArray;
  }
}
