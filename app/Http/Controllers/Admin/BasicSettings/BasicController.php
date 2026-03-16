<?php

namespace App\Http\Controllers\Admin\BasicSettings;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\MailFromAdminRequest;
use App\Models\Admin;
use App\Models\Timezone;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class BasicController extends Controller
{
  public function setLocaleAdmin(Request $request)
  {
    Session::put('admin_lang', 'admin_' . $request->code);
    $admin_id = Auth::guard('admin')->user()->id;
    $admin = Admin::find($admin_id);
    $admin->lang_code = $request->code;
    $admin->save();
    return $request->code;
  }

  //general_settings
  public function generalSettings()
  {
    $data = [];
    $data['data'] = DB::table('basic_settings')->first();

    $data['timezones'] = Timezone::get();
    return view('admin.basic-settings.general_settings', $data);
  }

  //update general settings

  public function updateGeneralSetting(Request $request)
  {
   
    $data = DB::table('basic_settings')->first();

    $rules = [
      'time_format_id' => 'required',
      'timezone_id' => 'required',
      'website_title' => 'required|max:255',
      'email_address' => 'nullable|email',
      'contact_number' => 'nullable',
      'address' => 'nullable',
      'latitude' => 'nullable|numeric',
      'longitude' => 'nullable|numeric',
      'base_currency_symbol' => 'required',
      'base_currency_symbol_position' => 'required',
      'base_currency_text' => 'required',
      'base_currency_text_position' => 'required',
      'base_currency_rate' => 'required|numeric|min:1',
      'theme_version' => 'required|numeric',
      'primary_color' => 'required',
      'secondary_color' => 'required',
      'breadcrumb_overlay_color' => 'required',
      'breadcrumb_overlay_opacity' => 'required|numeric|min:0|max:1',
      'preloader_status' => 'required',
    ];

    // Convert base_currency_text to uppercase if it's lowercase
    if (ctype_lower($request->base_currency_text)) {
      $request->merge(['base_currency_text' => strtoupper($request->base_currency_text)]);
    }

    // Check for required fields based on existing data
    $conditionalFields = ['logo', 'favicon'];
    foreach ($conditionalFields as $field) {
      if (is_null($data->$field)) {
        $rules[$field] = 'required';
      }
      if ($request->hasFile($field)) {
        $rules[$field] = new ImageMimeTypeRule();
      }
    }
   

    // Add preloader rule conditionally based on preloader_status
    if ($request->preloader_status == 1) { 
      if (($data->preloader == '') && !$request->filled('preloader')) {
        $rules['preloader'] = 'required';
      }
      if ($request->hasFile('preloader')) {
        $rules['preloader'] = new ImageMimeTypeRule();
      }
    }

    // Validate the request
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    // Handle file uploads, these data merge in the updateOrInsert method
    $uploadedFiles = [
      'favicon' => $data->favicon,
      'logo' => $data->logo,
      'preloader' => $data->preloader,
    ];

    foreach ($uploadedFiles as $field => $currentName) {
      if ($request->hasFile($field)) {
        $uploadedFiles[$field] = UploadFile::update('./assets/img/', $request->file($field), $currentName);
      }
    }

    // Update or insert data to basic_settings table
    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      array_merge($uploadedFiles, [
        'preloader_status' => $request->preloader_status,
        'website_title' => $request->website_title,
        'email_address' => $request->email_address,
        'contact_number' => $request->contact_number,
        'address' => $request->address,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'base_currency_symbol' => $request->base_currency_symbol,
        'base_currency_symbol_position' => $request->base_currency_symbol_position,
        'base_currency_text' => $request->base_currency_text,
        'base_currency_text_position' => $request->base_currency_text_position,
        'base_currency_rate' => $request->base_currency_rate,
        'theme_version' => $request->theme_version,
        'primary_color' => $request->primary_color,
        'secondary_color' => $request->secondary_color,
        'breadcrumb_overlay_color' => $request->breadcrumb_overlay_color,
        'breadcrumb_overlay_opacity' => $request->breadcrumb_overlay_opacity,
        'time_format' => $request->time_format_id,
        'time_zone' => $request->timezone_id,
      ])
    );

    // Update timezone settings
    Timezone::query()->where('is_set', 'yes')->update(['is_set' => 'no']);
    $newTimezone = Timezone::query()->find($request->timezone_id);
    $newTimezone->update(['is_set' => 'yes']);

    // Set environment variable
    setEnvironmentValue(['TIMEZONE' => $newTimezone->timezone]);
    Artisan::call('config:clear');

    Session::flash('success', __('Update general settings successfully') . '!');
    return redirect()->back();
  }


  public function mailFromAdmin()
  {
    $data = DB::table('basic_settings')
      ->select('smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->first();
    

    return view('admin.basic-settings.email.mail-from-admin', ['data' => $data]);
  }

  public function updateMailFromAdmin(MailFromAdminRequest $request)
  {
    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'smtp_status' => $request->smtp_status,
        'smtp_host' => $request->smtp_host,
        'smtp_port' => $request->smtp_port,
        'encryption' => $request->encryption,
        'smtp_username' => $request->smtp_username,
        'smtp_password' => $request->smtp_password,
        'from_mail' => $request->from_mail,
        'from_name' => $request->from_name
      ]
    );

    $request->session()->flash('success', __('Mail info updated successfully') . '!');

    return redirect()->back();
  }

  public function mailToAdmin()
  {
    $data = DB::table('basic_settings')->select('to_mail')->first();

    return view('admin.basic-settings.email.mail-to-admin', ['data' => $data]);
  }

  public function updateMailToAdmin(Request $request)
  {
    $rule = [
      'to_mail' => 'required'
    ];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      ['to_mail' => $request->to_mail]
    );

    $request->session()->flash('success', __('Mail info updated successfully') . '!');

    return redirect()->back();
  }

  public function plugins()
  {
    $data = DB::table('basic_settings')
      ->select('disqus_status', 'disqus_short_name', 'google_recaptcha_status', 'google_recaptcha_site_key', 'google_recaptcha_secret_key', 'whatsapp_status', 'whatsapp_number', 'whatsapp_header_title', 'whatsapp_popup_status', 'whatsapp_popup_message', 'facebook_login_status', 'facebook_app_id', 'facebook_app_secret', 'google_login_status', 'google_client_id', 'google_client_secret', 'google_map_api_key', 'google_map_api_key_status', 'google_map_radius')
      ->first();

    return view('admin.basic-settings.plugins', ['data' => $data]);
  }

  public function updateDisqus(Request $request)
  {
    $rules = [
      'disqus_status' => 'required',
      'disqus_short_name' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'disqus_status' => $request->disqus_status,
        'disqus_short_name' => $request->disqus_short_name
      ]
    );

    $request->session()->flash('success', __('Disqus info updated successfully') . '!');

    return redirect()->back();
  }
  public function updateGoogleMap(Request $request)
  {
    $rules = [
      'google_map_api_key_status' => 'required',
      'google_map_api_key' => 'required',
      'google_map_radius' => 'required|numeric',
    ];


    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'google_map_api_key_status' => $request->google_map_api_key_status,
        'google_map_api_key' => $request->google_map_api_key,
        'google_map_radius' => $request->google_map_radius,
      ]
    );

    $request->session()->flash('success', __('Google Map API info updated successfully') . '!');

    return redirect()->back();
  }

  public function updateRecaptcha(Request $request)
  {
    $rules = [
      'google_recaptcha_status' => 'required',
      'google_recaptcha_site_key' => 'required',
      'google_recaptcha_secret_key' => 'required'
    ];


    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'google_recaptcha_status' => $request->google_recaptcha_status,
        'google_recaptcha_site_key' => $request->google_recaptcha_site_key,
        'google_recaptcha_secret_key' => $request->google_recaptcha_secret_key
      ]
    );

    $array = [
      'NOCAPTCHA_SECRET' => $request->google_recaptcha_secret_key,
      'NOCAPTCHA_SITEKEY' => $request->google_recaptcha_site_key
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', __('Recaptcha info updated successfully') . '!');

    return redirect()->back();
  }

  public function updateWhatsApp(Request $request)
  {
    $rules = [
      'whatsapp_status' => 'required',
      'whatsapp_number' => 'required',
      'whatsapp_header_title' => 'required',
      'whatsapp_popup_status' => 'required',
      'whatsapp_popup_message' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'whatsapp_status' => $request->whatsapp_status,
        'whatsapp_number' => $request->whatsapp_number,
        'whatsapp_header_title' => $request->whatsapp_header_title,
        'whatsapp_popup_status' => $request->whatsapp_popup_status,
        'whatsapp_popup_message' => Purifier::clean($request->whatsapp_popup_message)
      ]
    );

    $request->session()->flash('success', __('WhatsApp info updated successfully') . '!');

    return redirect()->back();
  }

  public function updateFacebook(Request $request)
  {
    $rules = [
      'facebook_login_status' => 'required',
      'facebook_app_id' => 'required',
      'facebook_app_secret' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'facebook_login_status' => $request->facebook_login_status,
        'facebook_app_id' => $request->facebook_app_id,
        'facebook_app_secret' => $request->facebook_app_secret
      ]
    );

    $array = [
      'FACEBOOK_CLIENT_ID' => $request->facebook_app_id,
      'FACEBOOK_CLIENT_SECRET' => $request->facebook_app_secret,
      'FACEBOOK_CALLBACK_URL' => url('/login/facebook/callback')
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', __('Facebook info updated successfully') . '!');

    return redirect()->back();
  }

  public function updateGoogle(Request $request)
  {
    $rules = [
      'google_login_status' => 'required',
      'google_client_id' => 'required',
      'google_client_secret' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'google_login_status' => $request->google_login_status,
        'google_client_id' => $request->google_client_id,
        'google_client_secret' => $request->google_client_secret
      ]
    );

    $array = [
      'GOOGLE_CLIENT_ID' => $request->google_client_id,
      'GOOGLE_CLIENT_SECRET' => $request->google_client_secret,
      'GOOGLE_CALLBACK_URL' => url('/login/google/callback')
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', __('Google info updated successfully') . '!');

    return redirect()->back();
  }

  public function updatePusher(Request $request)
  {
    $rules = [
      'pusher_app_id' => 'required',
      'pusher_key' => 'required',
      'pusher_secret' => 'required',
      'pusher_cluster' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'pusher_app_id' => $request->pusher_app_id,
        'pusher_key' => $request->pusher_key,
        'pusher_secret' => $request->pusher_secret,
        'pusher_cluster' => $request->pusher_cluster
      ]
    );

    $array = [
      'PUSHER_APP_ID' => $request->pusher_app_id,
      'PUSHER_APP_KEY' => $request->pusher_key,
      'PUSHER_APP_SECRET' => $request->pusher_secret,
      'PUSHER_APP_CLUSTER' => $request->pusher_cluster
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', __('Pusher info updated successfully') . '!');

    return redirect()->back();
  }


  public function maintenance()
  {
    $data = DB::table('basic_settings')
      ->select('maintenance_img', 'maintenance_status', 'maintenance_msg', 'bypass_token')
      ->first();

    return view('admin.basic-settings.maintenance', ['data' => $data]);
  }

  public function updateMaintenance(Request $request)
  {

    $data = DB::table('basic_settings')->select('maintenance_img')->first();
    $rules = $messages = [];
    if (!$request->filled('maintenance_img') && is_null($data->maintenance_img)) {
      $rules['maintenance_img'] = 'required';
    }
    if ($request->hasFile('maintenance_img')) {
      $rules['maintenance_img'] = new ImageMimeTypeRule();
    }
    $rules['maintenance_status'] = 'required';
    $rules['maintenance_msg'] = 'required';
    
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }
    if ($request->hasFile('maintenance_img')) {
      $imageName = UploadFile::update('assets/img/', $request->file('maintenance_img'), $data->maintenance_img);
    }
    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'maintenance_img' => $request->hasFile('maintenance_img') ? $imageName : $data->maintenance_img,
        'maintenance_status' => $request->maintenance_status,
        'maintenance_msg' => Purifier::clean($request->maintenance_msg),
        'bypass_token' => $request->bypass_token
      ]
    );
    $down = "down";
    if ($request->filled('bypass_token')) {
      $down .= " --secret=" . $request->bypass_token;
    }
    if ($request->maintenance_status == 1) {
      Artisan::call('up');
      Artisan::call($down);
      Artisan::call('view:clear');
      Artisan::call('cache:clear');
      Artisan::call('config:clear');
    } else {
      Artisan::call('up');
    }
    Session::flash('success', __('Maintenance Info updated successfully') . '!');
    return redirect()->back();
  }

  public function productTaxAmount()
  {
    $data = DB::table('basic_settings')->select('product_tax_amount')->first();

    return view('admin.shop.tax', ['data' => $data]);
  }

  public function updateProductTaxAmount(Request $request)
  {
    $rules = [
      'product_tax_amount' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    // store the tax amount info into db
    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      ['product_tax_amount' => $request->product_tax_amount]
    );

    $request->session()->flash('success', __('Tax amount updated successfully') . '!');

    return redirect()->back();
  }

  //this code for shop
  public function settings()
  {
    $info = DB::table('basic_settings')->select('shop_status')->first();
    return view('admin.shop.settings', ['info' => $info]);
  }
  public function updateSettings(Request $request)
  {
    $rules = [
      'shop_status' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    // store the tax amount info into db
    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      ['shop_status' => $request->shop_status]
    );

    Session::flash('success', __('Updated shop settings successfully') . '!');

    return redirect()->back();
  }

  public function getCountries(Request $request)
  {
    
    
    $languageId = $request->language_id;
    $perPage = $request->get('per_page', 10); 

    $countries = \App\Models\Country::where([
      ['language_id', $languageId],
      ['status', 1],
    ])->paginate($perPage);

    return response()->json([
      "results" => $countries->items(),
      "pagination" => [
        "more" => $countries->hasMorePages()
      ]
    ]);
  }


  public function getStates(Request $request)
  {
    $languageId = $request->language_id;
    $countryId = $request->countryId;
    $perPage = $request->get('per_page', 10); 

    $query = \App\Models\State::where([
      ['language_id', $languageId],
      ['status', 1],
    ]);

    if ($countryId) {
      $query->where('country_id', $countryId);
    }

    $states = $query->paginate($perPage);

    return response()->json([
      "results" => $states->items(),
      "pagination" => [
        "more" => $states->hasMorePages()
      ]
    ]);
  }

  public function getCities(Request $request)
  {
    $languageId = $request->language_id;
    $countryId = $request->countryId;
    $stateId = $request->stateId;
    $perPage = $request->get('per_page', 10); 
    
    $query = \App\Models\City::where([
      ['language_id', $languageId],
      ['status', 1],
    ]);

    if ($stateId) {
      $query->where('state_id', $stateId);
    } elseif ($countryId) {
      $query->where('country_id', $countryId);
    }

    $cities = $query->paginate($perPage);
  

    return response()->json([
      "results" => $cities->items(),
      "pagination" => [
        "more" => $cities->hasMorePages()
      ]
    ]);
  }
}
