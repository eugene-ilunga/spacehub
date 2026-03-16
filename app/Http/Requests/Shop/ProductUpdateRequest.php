<?php

namespace App\Http\Requests\Shop;

use App\Models\Language;
use App\Models\Shop\Product;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ProductUpdateRequest extends FormRequest
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
    $product = Product::query()->find($this->id);
    $sliderImages = json_decode($product->slider_images);

    $ruleArray = [
      'slider_images' => count($sliderImages) == 0 && empty($this->slider_images) ? 'required' : '',
      'thumbnail_image' => $this->hasFile('thumbnail_image') ? new ImageMimeTypeRule() : '',
      'status' => 'required'
    ];
    if ($this->input('product_type') != 'physical') {
      $ruleArray['input_type'] = 'required';
    }

    if ($this->input_type == 'upload' && empty($product->file)) {
      $ruleArray['file'] = 'required';
    }
    if ($this->hasFile('file')) {
      $ruleArray['file'] = 'mimes:zip';
    }

    $ruleArray['link'] = 'required_if:input_type,link';
    $ruleArray['current_price'] = 'required|numeric';

    $langForValidation  = Language::query()->where('is_default', '=', 1)->firstOrFail();
    $defaultLanguageCode = $langForValidation->code;
    $languages = Language::all();

    foreach ($languages as $language) {
      // Always require fields for the default language
      if ($language->code === $defaultLanguageCode) {
        $ruleArray[$language->code . '_title'] = [
          'required',
          'max:255',
          Rule::unique('product_contents', 'title')->ignore($this->id, 'product_id')
        ];
        $ruleArray[$language->code . '_category_id'] = 'required';
        $ruleArray[$language->code . '_summary'] = 'required';
        $ruleArray[$language->code . '_content'] = 'min:30';
      } else {
        // For other languages, check if any field is filled
        if (
          $this->filled($language->code . '_title') ||
          $this->filled($language->code . '_summary') ||
          $this->filled($language->code . '_meta_keyword') ||
          $this->filled($language->code . '_meta_description') ||
          $this->filled($language->code . '_content')
        ) {
          $ruleArray[$language->code . '_title'] = [
            'required',
            'max:255',
            Rule::unique('product_contents', 'title')->ignore($this->id, 'product_id')
          ];
          $ruleArray[$language->code . '_category_id'] = 'required';
          $ruleArray[$language->code . '_summary'] = 'required';
          $ruleArray[$language->code . '_content'] = 'min:30';
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
    $messageArray = [
      'file.required_if' => __('The file field is required when input type is upload') . '.',
      'file.mimes' => __('Only') . ' ' . '.zip' . ' ' . __('file is allowed for product') . '.',
      'link.required_if' => __('The download link field is required when input type is link') . '.',
    ];

    $languages = Language::all();

    foreach ($languages as $language) {
      $messageArray[$language->code . '_title.required'] = __('The title field is required for') . ' ' . $language->name . ' ' . __('language') . '.';
      $messageArray[$language->code . '_title.max'] = __('The title field cannot contain more than 255 characters for') . ' ' . $language->name . ' ' . __('language') . '.';
      $messageArray[$language->code . '_title.unique'] = __('The title field must be unique for') . ' ' . $language->name . ' ' . __('language') . '.';
      $messageArray[$language->code . '_category_id.required'] = __('The category field is required for') . ' ' . $language->name . ' ' . __('language') . '.';
      $messageArray[$language->code . '_summary.required'] = __('The summary field is required for') . ' ' . $language->name . ' ' . __('language') . '.';
      $messageArray[$language->code . '_content.min'] = __('The content must be at least 30 characters for') . ' ' . $language->name . ' ' . __('language') . '.';
    }

    return $messageArray;
  }
}
