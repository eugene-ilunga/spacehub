<?php

namespace App\Http\Requests\User;

use App\Rules\ImageMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
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
    return [
      'image' => $this->hasFile('image') ? [
        new ImageMimeTypeRule(),
      ] : '',
      'first_name' => 'required',
      'username' => 'required|unique:users,username,' . Auth::guard('web')->user()->id,
      'last_name' => 'required',
      'phone_number' => 'required',
      'address' => 'required',
      'city' => 'required',
      'country' => 'required'
    ];
  }
}
