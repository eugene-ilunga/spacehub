<?php

namespace App\Http\Controllers\Admin\HomePage;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactContent;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class ContactController extends Controller
{
    public function index()
    {
      $data['data'] = DB::table('contacts')
        ->select('mobile_number', 'email_address')
        ->first();
      $language = getAdminLanguage();
      $data['language']= $language;
      $data['contactContent'] =$language->contactContent()->first();
      $data['langs'] = Language::all();
      return view('admin.home-page.contact.index', $data);
    }
    public function updateInfo(Request $request)
    {
      $rules = [
        'mobile_number' => 'nullable',
        'email_address' => 'nullable',
      ];

      $validator = Validator::make($request->all(), $rules);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator->errors());
      }


      $data = Contact::first();
      if (empty($data)){
        $data = new Contact();
      }
      $data->mobile_number = $request->mobile_number;
      $data->email_address = $request->email_address;
      $data->save();

      $request->session()->flash('success', __('contact Information updated successfully') . '!');

      return redirect()->back();

    }

  public function updateContentInfo(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();

    ContactContent::query()->updateOrCreate(
      ['language_id' => $language->id],
      [
        'title' => $request->title,
        'text' => Purifier::clean($request->text, 'youtube'),
        'location' => $request->location,
      ]
    );

    $request->session()->flash('success', __('contact Information updated successfully') . '!');

    return redirect()->back();
  }

}
