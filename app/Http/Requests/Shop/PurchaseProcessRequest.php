<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseProcessRequest extends FormRequest
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
      'billing_first_name' => 'required',
      'billing_last_name' => 'required',
      'billing_email_address' => 'required|email:rfc,dns',
      'billing_phone_number' => 'required',
      'billing_address' => 'required',
      'billing_city' => 'required',
      'billing_country' => 'required'
    ];

    if ($this->gateway == 'stripe') {
      $ruleArray['stripeToken'] = 'required';
    }
    return $ruleArray;
  }

  /**
   * Get the validation messages that apply to the request.
   *
   * @return array
   */
}
