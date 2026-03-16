<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\SellerPermissionHelper;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\Seller;
use App\Models\SellerInfo;
use App\Models\Space;
use App\Models\SpaceBooking;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DateTime;
use Mews\Purifier\Facades\Purifier;


class VendorController extends Controller
{
  //signup
  public function signup()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_vendor_signup as meta_keywords', 'meta_description_vendor_signup as meta_description')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();
    $queryResult['direction'] = $language->direction;

    $queryResult['recaptchaStatus'] = Basic::query()->pluck('google_recaptcha_status')->first();
    return view('vendors.auth.register', $queryResult);
  }

  //create
  public function create(Request $request)
  {
    $admin = Admin::select('username')->first();
    $admin_username = $admin->username;
    $rules = [
      'name' => 'required',
      'phone' => 'required',
      'username' => "required|unique:sellers|not_in:$admin_username",
      'email' => 'required|email|unique:sellers',
      'password' => 'required|confirmed|min:6',
      'password_confirmation' => 'required',
    ];

    $message = [
      'g-recaptcha-response.required' => __('Please verify that you are not a robot') . '.',
      'g-recaptcha-response.captcha' => __('Captcha error! try again later or contact site admin') . '.'
    ];
    $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('seller_email_verification', 'seller_admin_approval', 'google_recaptcha_status')->first();

    if ($setting->google_recaptcha_status == 1) {
      $rules['g-recaptcha-response'] = 'required|captcha';
    }

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $in = $request->all();
    
    if ($setting->seller_email_verification == 1) {
      // first, get the mail template information from db
      $mailTemplate = MailTemplate::where('mail_type', 'verify_email')->first();

      $mailData['subject'] = $mailTemplate->mail_subject;
      $mailBody = $mailTemplate->mail_body;

      // second, send a password reset link to user via email
      $info = DB::table('basic_settings')
        ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
        ->first();

      $name = $request->name;
      $token = $request->email;
      $link = '<a href="' . route('vendor.account.verify', ['token' => $token]) . '">' . __('Click Here') . '</a>';
      $mailBody = str_replace('{username}', $name, $mailBody);
      $mailBody = str_replace('{verification_link}', $link, $mailBody);
      $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);
      $mailData['body'] = $mailBody;
      $mailData['recipient'] = $request->email;

      $mailData['sessionMessage'] = __('A mail has been sent to your email address') . '.';
      
      BasicMailer::sendMail($mailData);
      $in['status'] = 0;
    } else {
      Session::flash('success', __('Sign up successfully completed') . '. ' . __('Please Login Now'));
    }
    if ($setting->seller_admin_approval == 1) {
      $in['status'] = 0;
    }

    if ($setting->seller_admin_approval == 0 && $setting->seller_email_verification == 0) {
      $in['status'] = 1;
    }

    $in['password'] = Hash::make($request->password);
    $in['recipient_mail'] = $request->email;
    $seller = Seller::create($in);
    $languages = Language::get();
    foreach ($languages as $language) {
      $vendor_info = new SellerInfo();
      $vendor_info->language_id = $language->id;
      $vendor_info->seller_id = $seller->id;
      $vendor_info->name = $request->name;
      $vendor_info->save();
    }

    return redirect()->route('vendor.login');
  }

  //login
  public function login(Request $request)
  {

    if ($request->filled('redirect')) {
      if ($request->redirect == 'buy_plan') {
        Session::put('redirectUrl', route('vendor.plan.extend.index'));
      }
    }
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();
    $queryResult['language'] = $language;

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_vendor_login as meta_keywords', 'meta_description_vendor_login as meta_description')->first();
    $queryResult['pageHeading'] = $misc->getPageHeading($language);
    $queryResult['direction'] = $language->direction;

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $queryResult['recaptchaStatus'] = Basic::query()->pluck('google_recaptcha_status')->first();
    $queryResult['bs'] = Basic::query()->select('google_recaptcha_status', 'facebook_login_status', 'google_login_status')->first();
    return view('vendors.auth.login', $queryResult);
  }

  //authenticate
  public function authentication(Request $request)
  {
    $language = getVendorLanguage();
    $info = Basic::select('google_recaptcha_status')->first();
    $rules = [
      'username' => 'required',
      'password' => 'required',
    ];
    if ($info->google_recaptcha_status == 1) {
      $rules['g-recaptcha-response'] = 'required|captcha';
    }


    $messages = [];

    if ($info->google_recaptcha_status == 1) {
      $messages['g-recaptcha-response.required'] = __('Please verify that you are not a robot') . '.';
      $messages['g-recaptcha-response.captcha'] = __("Captcha error! try again later or contact site admin"). '.';
    }
    $validator = Validator::make($request->all(), $rules, $messages);


    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if (
      Auth::guard('seller')->attempt([
        'username' => $request->username,
        'password' => $request->password
      ])
    ) {
      $authAdmin = Auth::guard('seller')->user();

      $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('seller_email_verification', 'seller_admin_approval')->first();

      // check whether the admin's account is active or not
      if ($setting->seller_email_verification == 1 && $authAdmin->email_verified_at == NULL && $authAdmin->status == 0) {
        Session::flash('error', __('Please Verify Your Email Address') . '!');

        // logout auth admin as condition not satisfied
        Auth::guard('seller')->logout();
        Session::forget('secret_login');

        return redirect()->back();
      } elseif ($setting->seller_email_verification == 0 && $setting->seller_admin_approval == 1) {
        if (Session::has('redirectUrl')) {
          return redirect()->route('vendor.plan.extend.index');
        } else {
          return redirect()->route('vendor.dashboard', ['language' => $language->code]);
        }
      } else {
        if (Session::has('redirectUrl')) {
          return redirect()->route('vendor.plan.extend.index');
        } else {
          return redirect()->route('vendor.dashboard', ['language' => $language->code]);
        }
      }
    } else {
      return redirect()->back()->with('error', __('Oops, Username or password does not match') . '!');
    }
  }

  //confirm_email'
  public function confirm_email()
  {
    $language = getVendorLanguage();
    $email = request()->input('token');
    $seller = Seller::where('email', $email)->first();
    $seller->email_verified_at = now();
    $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('seller_admin_approval')->first();
    if ($setting->seller_admin_approval != 1) {
      $seller->status = 1;
    }

    $seller->save();
    Auth::guard('seller')->login($seller);
    return redirect()->route('vendor.dashboard', ['language' => $language->code]);
  }

  public function logout(Request $request)
  {

    Auth::guard('seller')->logout();
    Session::forget('secret_login');
    Session::forget('vendor_lang');
    $language = getVendorLanguage();
    return redirect()->route('vendor.login', ['language' => $language->code]);
  }

  public function dashboard()
  {
    $seller_id = Auth::guard('seller')->user()->id;
  
    $information['admin_setting'] = DB::table('basic_settings')->where('uniqid', 12345)->select('seller_admin_approval', 'admin_approval_notice')->first();

    $payment_logs = Membership::where('seller_id', $seller_id)->get()->count();

    //package start
    $nextPackageCount = Membership::query()->where([
      ['seller_id', Auth::guard('seller')->user()->id],
      ['expire_date', '>=', Carbon::now()->toDateString()]
    ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count();
    //current package
    $information['current_membership'] = Membership::query()->where([
      ['seller_id', Auth::guard('seller')->user()->id],
      ['start_date', '<=', Carbon::now()->toDateString()],
      ['expire_date', '>=', Carbon::now()->toDateString()]
    ])->where('status', 1)->whereYear('start_date', '<>', '9999')->first();
    if ($information['current_membership'] != null) {
      $countCurrMem = Membership::query()->where([
        ['seller_id', Auth::guard('seller')->user()->id],
        ['start_date', '<=', Carbon::now()->toDateString()],
        ['expire_date', '>=', Carbon::now()->toDateString()]
      ])->where('status', 1)->whereYear('start_date', '<>', '9999')->count();
      if ($countCurrMem > 1) {
        $information['next_membership'] = Membership::query()->where([
          ['seller_id', Auth::guard('seller')->user()->id],
          ['start_date', '<=', Carbon::now()->toDateString()],
          ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
      } else {
        $information['next_membership'] = Membership::query()->where([
          ['seller_id', Auth::guard('seller')->user()->id],
          ['start_date', '>', $information['current_membership']->expire_date]
        ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
      }
      $information['next_package'] = $information['next_membership'] ? Package::query()->where('id', $information['next_membership']->package_id)->first() : null;
    } else {
      $information['next_package'] = null;
    }
    $information['current_package'] = $information['current_membership'] ? Package::query()->where('id', $information['current_membership']->package_id)->first() : null;
    $information['package_count'] = $nextPackageCount;
    $information['spaces'] = Space::query()->where('seller_id', Auth::guard('seller')->user()->id)->count();
    $information['spaceBookings'] = SpaceBooking::query()->where('seller_id', Auth::guard('seller')->user()->id)->count();

    $monthWiseSpaceBookings = DB::table('space_bookings')
      ->select(DB::raw('month(created_at) as month'), DB::raw('count(id) as total_space_bookings'))
      ->where([['payment_status', '=', 'completed'], ['seller_id', Auth::guard('seller')->user()->id]])
      ->groupBy('month')
      ->whereYear('created_at', '=', date('Y'))
      ->get();

    $monthWiseSpaceIncomes = DB::table('space_bookings')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where([['payment_status', '=', 'completed'], ['seller_id', Auth::guard('seller')->user()->id]])
      ->groupBy('month')
      ->whereYear('created_at', '=', date('Y'))
      ->get();
    $months = [];
    $totalSpaceIncomes = [];
    $totalSpaceBookings = [];

    for ($i = 1; $i <= 12; $i++) {
      // get all 12 months name
      $monthNum = $i;
      $dateObj = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('M');
      array_push($months, $monthName);

      // get all 12 months's service orders
      $spaceBookingFound = false;

      foreach ($monthWiseSpaceBookings as $spaceBooking) {
        if ($spaceBooking->month == $i) {
          $spaceBookingFound = true;
          array_push($totalSpaceBookings, $spaceBooking->total_space_bookings);
          break;
        }
      }

      if ($spaceBookingFound == false) {
        array_push($totalSpaceBookings, 0);
      }
      // get all 12 months's service incomes
      $spaceIncomeFound = false;

      foreach ($monthWiseSpaceIncomes as $monthWiseSpaceIncome) {
        if ($monthWiseSpaceIncome->month == $i) {
          $spaceIncomeFound = true;
          array_push($totalSpaceIncomes, $monthWiseSpaceIncome->total);
          break;
        }
      }

      if ($spaceIncomeFound == false) {
        array_push($totalSpaceIncomes, 0);
      }
    }


    $information['months'] = $months;
    $information['totalSpaceBookings'] = $totalSpaceBookings;
    $information['totalSpaceIncomes'] = $totalSpaceIncomes;
    $information['payment_logs'] = $payment_logs;
    $information['transactions'] = Transaction::where('seller_id', Auth::guard('seller')->user()->id)->count();
    $information['support_tickets_count'] = SupportTicket::where([['user_id', Auth::guard('seller')->user()->id], ['user_type', 'seller']])->count();

    if ($seller_id) {
      $permissions = SellerPermissionHelper::currentPackagePermission($seller_id);
      if (!empty($permissions)) {
        $permissions = json_decode($permissions->package_feature, true);
        $permissions = is_array($permissions) ? $permissions : [];
      } else {
        $permissions = [];
      }
    }
    $information['canAccessSupportTicket'] =  array_key_exists('Support Tickets', $permissions);
    return view('vendors.index', $information);
  }

  //change_password
  public function change_password()
  {
    return view('vendors.auth.change-password');
  }

  //update_password
  public function updated_password(Request $request)
  {
    $rules = [
      'current_password' => [
        'required',
        new MatchOldPasswordRule('seller')
      ],
      'new_password' => 'required|confirmed',
      'new_password_confirmation' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return response()->json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $seller = Auth::guard('seller')->user();

    $seller->update([
      'password' => Hash::make($request->new_password)
    ]);

    Session::flash('success', __('Password updated successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }

  //edit_profile
  public function edit_profile()
  {
    if (request()->input('redirectPath') == 'membership-feature') {
      $url = url()->previous();
      if ($url != route('vendor.edit.profile')) {
        Session::put('redirectToMembershipFeature', $url);
      }
    }
    $information['languages'] = Language::get();
    $information['seller'] = Auth::guard('seller')->user();
    return view('vendors.auth.edit-profile', $information);
  }

  public function recipient_mail()
  {
    $information['seller'] = Auth::guard('seller')->user();
    return view('vendors.recipient-mail', $information);
  }

  public function update_recipient_mail(Request $request)
  {
    $rules = [
      'recipient_mail' => 'required'
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $seller = Seller::where('id', Auth::guard('seller')->user()->id)->first();
    $seller->recipient_mail = $request->recipient_mail;
    $seller->save();
    Session::flash('success', __('Recipient mail updated successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }

  //update_profile
  public function update_profile(Request $request, Seller $seller)
  {
    
    $id = Auth::guard('seller')->user()->id;
    $rules = [
      'username' => [
        'required',
        "not_in:admin",
        Rule::unique('sellers', 'username')->ignore($id)
      ],
      'email' => [
        'required',
        'email',
        Rule::unique('sellers', 'email')->ignore($id)
      ]
    ];

    if ($request->hasFile('photo')) {
      $rules['photo'] = 'mimes:png,jpeg,jpg';
    }
    
    $languages = Language::get();
    $message = [];
    foreach ($languages as $language) {
      $rules[$language->code . '_name'] = 'required';
      $message[$language->code . '_name.required'] = __('The') . ' ' . $language->name . ' ' . __('name is required') . '.';
    }

    $validator = Validator::make($request->all(), $rules, $message);
    if ($validator->fails()) {
      return response()->json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $in = $request->all();
    $seller = Seller::where('id', $id)->first();
    $file = $request->file('photo');
    if ($file) {
      $extension = $file->getClientOriginalExtension();
      $directory = public_path('assets/admin/img/seller-photo/');
      $fileName = uniqid() . '.' . $extension;
      @mkdir($directory, 0775, true);
      $file->move($directory, $fileName);

      @unlink(public_path('assets/admin/img/seller-photo/') . $seller->photo);
      $in['photo'] = $fileName;
    }

    if ($request->show_email_addresss) {
      $in['show_email_addresss'] = 1;
    } else {
      $in['show_email_addresss'] = 0;
    }
    if ($request->show_phone_number) {
      $in['show_phone_number'] = 1;
    } else {
      $in['show_phone_number'] = 0;
    }
    if ($request->show_contact_form) {
      $in['show_contact_form'] = 1;
    } else {
      $in['show_contact_form'] = 0;
    }

    $seller->update($in);

    foreach ($languages as $language) {
      $sellerInfo = SellerInfo::where('seller_id', Auth::guard('seller')->user()->id)->where('language_id', $language->id)->first();
      if (!$sellerInfo) {
        $sellerInfo = new SellerInfo();
        $sellerInfo->language_id = $language->id;
        $sellerInfo->seller_id = Auth::guard('seller')->user()->id;
      }
      $sellerInfo->name = $request[$language->code . '_name'];
      $sellerInfo->country = $request[$language->code . '_country'];
      $sellerInfo->city = $request[$language->code . '_city'];
      $sellerInfo->state = $request[$language->code . '_state'];
      $sellerInfo->zip_code = $request[$language->code . '_zip_code'];
      $sellerInfo->address = $request[$language->code . '_address'];
      $sellerInfo->details = Purifier::clean($request[$language->code . '_details'], 'youtube');
      $sellerInfo->save();
    }
    
    if ($request->session()->has('redirectToMembershipFeature')) {
      $redirectURL = $request->session()->get('redirectToMembershipFeature');
      return response()->json(['status' => 'membership-feature', 'redirect' => $redirectURL]);
    }

    Session::forget('redirectToMembershipFeature');

    Session::flash('success', __('Profile updated successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }

  public function changeTheme(Request $request)
  {
    Session::put('seller_theme_version', $request->seller_theme_version);
    return redirect()->back();
  }

  //transcation
  public function transcation(Request $request)
  {
    $transaction_id = null;
    if ($request->filled('transaction_id')) {
      $transaction_id = $request->transaction_id;
    }
    $transactions = Transaction::where('seller_id', Auth::guard('seller')->user()->id)->orderBy('id', 'desc')
      ->when($transaction_id, function ($query) use ($transaction_id) {
        return $query->where('transcation_id', 'like', '%' . $transaction_id . '%');
      })
      ->paginate(10);
      
    return view('vendors.transaction', compact('transactions'));
  }

  //forget_passord
  public function forget_passord()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();
    $queryResult['language'] = $language;

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_vendor_forget_password as meta_keywords', 'meta_description_vendor_forget_password as meta_description')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $queryResult['recaptchaStatus'] = Basic::query()->pluck('google_recaptcha_status')->first();
    $queryResult['bs'] = Basic::query()->select('google_recaptcha_status', 'facebook_login_status', 'google_login_status')->first();

    return view('vendors.auth.forget-password', $queryResult);
  }

  //forget_mail
  public function forget_mail(Request $request)
  {
    $rules = [
      'email' => [
        'required',
        'email:rfc,dns',
        new MatchEmailRule('seller')
      ]
    ];
    if (Session::has('currentLocaleCode')) {
      $locale = Session::get('currentLocaleCode');
    }
    app()->setLocale($locale); 

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $seller = Seller::where('email', $request->email)->first();

    // first, get the mail template information from db
    $mailTemplate = MailTemplate::where('mail_type', 'reset_password')->first();
    $mailSubject = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // second, send a password reset link to user via email
    $info = DB::table('basic_settings')
      ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->first();

    $name = $seller->name;
    $token = uniqid();
    DB::table('password_resets')->insert([
      'email' => $seller->email,
      'token' => $token,
    ]);

    $link = '<a href="' . url("vendor/reset-password?token=" . $token) . '">' . __('Click Here') . '</a>';

    $mailBody = str_replace('{customer_name}', $name, $mailBody);
    $mailBody = str_replace('{password_reset_link}', $link, $mailBody);
    $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

    $data['recipient'] = $request->email;
    $data['subject'] = $mailSubject;
    $data['body'] = $mailBody;
    $data['sessionMessage'] = __('A mail has been sent to your email address') . '.';

    BasicMailer::sendMail($data);

    return redirect()->back();
  }

  //reset_password
  public function reset_password()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();
    $queryResult['language'] = $language;

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_signup', 'meta_description_signup')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $queryResult['recaptchaStatus'] = Basic::query()->pluck('google_recaptcha_status')->first();
    $queryResult['bs'] = Basic::query()->select('google_recaptcha_status', 'facebook_login_status', 'google_login_status')->first();
    return view('vendors.auth.reset-password', $queryResult);
  }

  //update_password
  public function update_password(Request $request)
  {
    $rules = [
      'new_password' => 'required|confirmed',
      'new_password_confirmation' => 'required'
    ];


    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator);
    }

    $reset = DB::table('password_resets')->where('token', $request->token)->first();
    if ($reset) {
      $email = $reset->email;
    } else {
      Session::flash('error', __('Something went wrong') . '.');
      return back();
    }

    $vendor = Seller::where('email', $email)->first();

    $vendor->update([
      'password' => Hash::make($request->new_password)
    ]);
    DB::table('password_resets')->where('token', $request->token)->delete();
    Session::flash('success', __('Reset Your Password Successfully Completed') . '. ' . __('Please Login Now'));
    return redirect()->route('vendor.login');
  }

  //monthly  income
  public function monthly_income(Request $request)
  {
    if ($request->filled('year')) {
      $date = $request->input('year');
    } else {
      $date = date('Y');
    }

    // service orders
    $monthWiseTotalIncomes = DB::table('transactions')->where('seller_id', Auth::guard('seller')->user()->id)
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->whereIn('transcation_type', [1])
      ->where('payment_status', 'completed')
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();
      
    $monthWiseTaxes = DB::table('transactions')->where('seller_id', Auth::guard('seller')->user()->id)
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(tax) as total'))
      ->whereIn('transcation_type', [1])
      ->where('payment_status', 'completed')
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();

    // withdraw and balance substract
    $monthlyTotalExpences = DB::table('transactions')->where('seller_id', Auth::guard('seller')->user()->id)
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->whereIn('transcation_type', [2, 4])
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();

    // rejected withdraw
    $monthlyTotalRetuns = DB::table('transactions')->where('seller_id', Auth::guard('seller')->user()->id)
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where([['transcation_type', 2], ['payment_status', 'declined']])
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();

    $months = [];
    $incomes = [];
    $taxes = [];
    $expenses = [];
    $returns = [];
    for ($i = 1; $i <= 12; $i++) {
      // get all 12 months name
      $monthNum = $i;
      $dateObj = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('M');
      array_push($months, $monthName);

      // get all 12 months's income of room booking , package booking, balance add
      $incomeFound = false;
      foreach ($monthWiseTotalIncomes as $incomeInfo) {
        if ($incomeInfo->month == $i) {
          $incomeFound = true;
          array_push($incomes, $incomeInfo->total);
          break;
        }
      }
      if ($incomeFound == false) {
        array_push($incomes, 0);
      }
      $taxFound = false;
      foreach ($monthWiseTaxes as $taxInfo) {
        if ($taxInfo->month == $i) {
          $taxFound = true;
          array_push($taxes, $taxInfo->total);
          break;
        }
      }
      if ($taxFound == false) {
        array_push($taxes, 0);
      }

      //get 12 month's expenses
      $expensesFound = false;
      foreach ($monthlyTotalExpences as $expenseInfo) {
        if ($expenseInfo->month == $i) {
          $expensesFound = true;
          array_push($expenses, $expenseInfo->total);
          break;
        }
      }
      if ($expensesFound == false) {
        array_push($expenses, 0);
      }

      //get 12 month's returns
      $returnFound = false;
      foreach ($monthlyTotalRetuns as $monthlyTotalRetun) {
        if ($monthlyTotalRetun->month == $i) {
          $returnFound = true;
          array_push($returns, $monthlyTotalRetun->total);
          break;
        }
      }

      if ($returnFound == false) {
        array_push($returns, 0);
      }
    }
    $information['months'] = $months;
    $information['incomes'] = $incomes;
    $information['taxes'] = $taxes;
    $information['expenses'] = $expenses;
    $information['returns'] = $returns;

    return view('vendors.income', $information);
  }

  public function subscription_log(Request $request)
  {
    $search = $request->search;
    $data['memberships'] = Membership::query()->when($search, function ($query, $search) {
      return $query->where('transaction_id', 'like', '%' . $search . '%');
    })
      ->where('seller_id', Auth::guard('seller')->user()->id)
      ->orderBy('id', 'DESC')
      ->paginate(10);
    return view('vendors.subscription_log', $data);
  }
  public function setLocaleVendor(Request $request)
  {

    Session::put('vendor_lang', 'admin_' . $request->code);
    app()->setLocale('admin_' . $request->code);
    return $request->code;
  }
}
