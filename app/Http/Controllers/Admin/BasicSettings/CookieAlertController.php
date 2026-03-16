<?php

namespace App\Http\Controllers\Admin\BasicSettings;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\CookieAlert;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class CookieAlertController extends Controller
{
  public function cookieAlert(Request $request)
  {
    // first, get the language info from db
    $language = getAdminLanguage();
    $information['language'] = $language;

    // then, get the cookie alert info of that language from db
    $information['data'] = $language->cookieAlertInfo()->first();

    // get all the languages from db
    $information['langs'] = Language::all();

    return view('admin.basic-settings.cookie-alert', $information);
  }

  public function updateCookieAlert(Request $request)
  {
    $rules = [
      'cookie_alert_status' => 'required',
      'cookie_alert_btn_text' => 'required',
      'cookie_alert_text' => 'required'
    ];


    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    // first, get the language info from db
    $language = getAdminLanguage();

    CookieAlert::query()->updateOrCreate(
      ['language_id' => $language->id],
      [
        'cookie_alert_status' => $request['cookie_alert_status'],
        'cookie_alert_btn_text' => $request['cookie_alert_btn_text'],
        'cookie_alert_text' => Purifier::clean($request['cookie_alert_text'])
      ]
    );

    $request->session()->flash('success', __('Cookie alert info updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }
}
