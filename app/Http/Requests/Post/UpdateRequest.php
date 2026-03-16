<?php

namespace App\Http\Requests\Post;

use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
      'image' => $this->hasFile('image') ? new ImageMimeTypeRule() : '',
      'serial_number' => 'required|numeric',
      'status' => 'required',
    ];

    $languages = Language::all();

    $langForValidation  = Language::query()->where('is_default', '=', 1)->firstOrFail();
    $defaultLanguageCode = $langForValidation->code;

    foreach ($languages as $language) {

      $languageCode = $language->code;

      if ($languageCode === $defaultLanguageCode) {

        $ruleArray[$language->code . '_title'] = [
          'required',
          'max:255',
          Rule::unique('post_informations', 'title')->ignore($this->id, 'post_id')
        ];
        $ruleArray[$languageCode . '_author'] = 'required|max:255';
        $ruleArray[$languageCode . '_category_id'] = 'required';
        $ruleArray[$languageCode . '_content'] = 'min:30';
      } else {
        if (
          $this->filled($languageCode . '_title') ||
          $this->filled($languageCode . '_author') ||
          $this->filled($languageCode . '_category_id') ||
          $this->filled($languageCode . '_content')
        ) {
          $ruleArray[$language->code . '_title'] = [
            'required',
            'max:255',
            Rule::unique('post_informations', 'title')->ignore($this->id, 'post_id')
          ];
          $ruleArray[$languageCode . '_author'] = 'required|max:255';
          $ruleArray[$languageCode . '_category_id'] = 'required';
          $ruleArray[$languageCode . '_content'] = 'min:30';
        }
      }
    }

    return $ruleArray;
  }

  public function messages()
  {
    $messageArray = [];

    $languages = Language::all();

    foreach ($languages as $language) {
      $messageArray[$language->code . '_title.required'] = __('The title field is required for') . ' ' . $language->name . ' ' . __('language') . '.';

      $messageArray[$language->code . '_title.max'] = __('The title field cannot contain more than 255 characters for') . ' ' . $language->name . ' ' . __('language') . '.';

      $messageArray[$language->code . '_title.unique'] = __('The title field must be unique for') . ' ' . $language->name . ' ' . __('language') . '.';

      $messageArray[$language->code . '_author.required'] = __('The author field is required for') . ' ' . $language->name . ' ' .  __('language') . '.';

      $messageArray[$language->code . '_author.max'] = __('The author field cannot contain more than 255 characters for') . ' ' . $language->name . ' ' . __('language') . '.';

      $messageArray[$language->code . '_category_id.required'] = __('The category field is required for') . ' ' . $language->name . ' ' . __('language') . '.';

      $messageArray[$language->code . '_content.min'] = __('The content must be at least 30 characters for') . ' ' . $language->name . ' ' . __('language') . '.';
    }
    return $messageArray;
  }
}
