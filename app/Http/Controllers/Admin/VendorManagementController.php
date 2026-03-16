<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\SellerPermissionHelper;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\GlobalDay;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use App\Models\SellerInfo;
use App\Models\Space;
use App\Models\SpaceBooking;
use App\Models\SpaceFeature;
use App\Models\SpaceServiceContent;
use App\Models\SubService;
use App\Models\SubServiceContent;
use App\Models\SupportTicket;
use App\Models\TimeSlot;
use App\Models\Transaction;
use App\Models\Withdraw;
use App\Rules\ImageDimensions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mews\Purifier\Facades\Purifier;


class VendorManagementController extends Controller
{
  public function settings()
  {
    $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('seller_email_verification', 'seller_admin_approval', 'admin_approval_notice')->first();
    return view('admin.end-user.vendor.settings', compact('setting'));
  }

  //update_setting
  public function update_setting(Request $request)
  {
    if ($request->seller_email_verification) {
      $seller_email_verification = 1;
    } else {
      $seller_email_verification = 0;
    }
    if ($request->seller_admin_approval) {
      $seller_admin_approval = 1;
    } else {
      $seller_admin_approval = 0;
    }
    // finally, store the favicon into db
    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'seller_email_verification' => $seller_email_verification,
        'seller_admin_approval' => $seller_admin_approval,
        'admin_approval_notice' => $request->admin_approval_notice,
      ]
    );

    Session::flash('success', __('Update Settings Successfully') . '!');
    return response()->json(['status' => 'success'], 200);
  }

  public function index(Request $request)
  {
    $searchKey = null;

    if ($request->filled('info')) {
      $searchKey = $request['info'];
    }

    $sellers = Seller::when($searchKey, function ($query, $searchKey) {
      return $query->where('username', 'like', '%' . $searchKey . '%')
        ->orWhere('email', 'like', '%' . $searchKey . '%');
    })
      ->where('id', '!=', 0)
      ->orderBy('id', 'desc')
      ->paginate(10);


    return view('admin.end-user.vendor.index', compact('sellers'));
  }


  public function create(Request $request)
  {
    $information['languages'] = Language::get();
    $information['adminLanguage'] = getAdminLanguage();

    return view('admin.end-user.vendor.create', $information);
  }

  public function store(Request $request)
  {
 
    $admin = Admin::select('username')->first();
    $admin_username = $admin->username;
    $rules = [
      'username' => "required|unique:sellers|not_in:$admin_username",
      'email' => 'required|email|unique:sellers',
      'password' => 'required|min:6',
    ];


    if ( $request->hasFile('photo')) {
      $rules['photo'] = ['mimes:png,jpeg,jpg', new ImageDimensions(100, 100, 100, 100)];
    }

    $langForValidation  = Language::query()->where('is_default', '=', 1)->firstOrFail();
    $defaultLanguageCode = $langForValidation->code;
    $languages = Language::get();
    foreach ($languages as $language) {
      if ($language->code === $defaultLanguageCode) {
        $rules[$language->code . '_name'] = 'required';
      } else {
        if (
          $request->filled($language->code . '_name') ||
          $request->filled($language->code . '_skills') ||
          $request->filled($language->code . '_country') ||
          $request->filled($language->code . '_city') ||
          $request->filled($language->code . '_address') ||
          $request->filled($language->code . '_zip_code') ||
          $request->filled($language->code . '_details') ||
          $request->filled($language->code . '_state')
        ) {
          $rules[$language->code . '_name'] = 'required';
        }
      }
    }

    $messages = [];
    foreach ($languages as $language) {

      $messages[$language->code . '_name.required'] = __('The name field is required');
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $in = $request->all();
    $in['password'] = Hash::make($request->password);
    $in['status'] = 1;

    $file = $request->file('photo');
    if ($file) {
      $extension = $file->getClientOriginalExtension();
      $directory = public_path('assets/admin/img/seller-photo/');
      $fileName = uniqid() . '.' . $extension;
      @mkdir($directory, 0775, true);
      $file->move($directory, $fileName);
      $in['photo'] = $fileName;
    }
    $in['email_verified_at'] = Carbon::now();
    $in['recipient_mail'] = $request->email;
    $seller = Seller::create($in);

    $seller_id = $seller->id;
    foreach ($languages as $language) {
      $sellerInfo = new SellerInfo();
      $sellerInfo->language_id = $language->id;
      $sellerInfo->seller_id = $seller_id;
      $sellerInfo->name = $request[$language->code . '_name'];
      $sellerInfo->skills = $request[$language->code . '_skills'] != null ? json_encode($request[$language->code . '_skills']) : null;
      $sellerInfo->country = $request[$language->code . '_country'];
      $sellerInfo->city = $request[$language->code . '_city'];
      $sellerInfo->state = $request[$language->code . '_state'];
      $sellerInfo->zip_code = $request[$language->code . '_zip_code'];
      $sellerInfo->address = $request[$language->code . '_address'];
      $sellerInfo->details = Purifier::clean($request[$language->code . '_details']);
      $sellerInfo->save();
    }

    //send mail to seller
    $bs = Basic::select('website_title')->first();
    $mailer = new MegaMailer();
    $data = [
      'toMail' => $request->email,
      'toName' => $request->username,
      'username' => $request->username,
      'password' => $request->password,
      'user_type' => 'seller',
      'website_title' => $bs->website_title,
      'templateType' => 'add_user_by_admin'
    ];
    $mailer->mailFromAdmin($data);

    Session::flash('success', __('Add Vendor Successfully') . '!');
    return Response::json(['status' => 'success'], 200);
  }

  public function show($id)
  {

    $information['langs'] = Language::all();

    $currency_info = $this->getCurrencyInfo();
    $information['currency_info'] = $currency_info;

    $language = getAdminLanguage();
    $information['language'] = $language;
    $seller = Seller::with([
      'seller_info' => function ($query) use ($language) {
        return $query->where('language_id', $language->id);
      }
    ])->where('id', $id)->firstOrFail();
    $information['seller'] = $seller;

    $information['langs'] = Language::all();
    $information['packages'] = Package::query()->where([
      ['status', '1'],
      ['title', '!=', null],
    ])->get();
    $online = OnlineGateway::query()->where('status', 1)->get();
    $offline = OfflineGateway::where('status', 1)->get();
    $information['gateways'] = $online->merge($offline);

    $information['spaces'] = Space::query()->join('space_contents', 'spaces.id', '=', 'space_contents.space_id')
      ->join('space_categories', 'space_categories.id', '=', 'space_contents.space_category_id')
      ->where([
        ['space_contents.language_id', '=', $language->id],
        ['spaces.seller_id', '=', $seller->id]
      ])
      ->select('spaces.id', 'spaces.seller_id', 'spaces.address', 'space_contents.title', 'space_contents.language_id', 'space_contents.slug', 'space_categories.name as categoryName', 'space_categories.id as categoryId', 'spaces.is_featured')
      ->orderByDesc('spaces.id')
      ->get();
    if ($seller->id != null) {

      $information['currentPackage'] = SellerPermissionHelper::currentPackagePermission($seller->id);
    }
    return view('admin.end-user.vendor.details', $information);
  }

  public function updateAccountStatus(Request $request, $id)
  {

    $seller = Seller::findOrFail($id);
    if ($request->account_status == 1) {
      $seller->update(['status' => 1]);
    } else {
      $seller->update(['status' => 0]);
    }
    Session::flash('success', __('Account status updated successfully') . '!');

    return redirect()->back();
  }

  public function updateEmailStatus(Request $request, $id)
  {
    $seller = Seller::findOrFail($id);
    if ($request->email_status == 1) {
      $seller->update(['email_verified_at' => now()]);
    } else {
      $seller->update(['email_verified_at' => NULL]);
    }
    Session::flash('success', __('Email status updated successfully') . '!');

    return redirect()->back();
  }

  public function changePassword($id)
  {
    $sellerInfo = Seller::findOrFail($id);

    return view('admin.end-user.vendor.change-password', compact('sellerInfo'));
  }

  public function updatePassword(Request $request, $id)
  {
    $rules = [
      'new_password' => 'required|confirmed',
      'new_password_confirmation' => 'required'
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $seller = Seller::findOrFail($id);

    $seller->update([
      'password' => Hash::make($request->new_password)
    ]);

    Session::flash('success', __('Password updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function edit($id)
  {
    $information['languages'] = Language::get();
    $seller = Seller::where('id', $id)->firstOrFail();
    $information['seller'] = $seller;
    $information['currencyInfo'] = $this->getCurrencyInfo();
    $information['adminLanguage'] = getAdminLanguage();
    return view('admin.end-user.vendor.edit', $information);
  }

  //update
  public function update(Request $request, $id, Seller $seller)
  {
    $rules = [

      'username' => [
        'required',
        'not_in:admin',
        Rule::unique('sellers', 'username')->ignore($id),
      ],
      'email' => [
        'required',
        Rule::unique('sellers', 'email')->ignore($id)
      ],
      'recipient_mail' => [
        'required',
        Rule::unique('sellers', 'recipient_mail')->ignore($id)
      ]
    ];

    if ($request->hasFile('photo')) {
      $rules['photo'] = 'mimes:png,jpeg,jpg|dimensions:min_width=100,max_width=100,min_width=100,min_height=100';
    }

    $languages = Language::get();
    foreach ($languages as $language) {
      $rules[$language->code . '_name'] = 'required';
    }

    $messages = [];

    foreach ($languages as $language) {
      $messages[$language->code . '_name.required'] = __('The name field is required') . '.';
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }


    $in = $request->all();
    $seller = Seller::where('id', $id)->firstOrFail();
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

    $languages = Language::get();
    $seller_id = $seller->id;
    foreach ($languages as $language) {
      $SellerInfo = SellerInfo::where('seller_id', $seller_id)->where('language_id', $language->id)->first();
      if ($SellerInfo == NULL) {
        $SellerInfo = new SellerInfo();
      }
      $SellerInfo->language_id = $language->id;
      $SellerInfo->seller_id = $seller_id;
      $SellerInfo->name = $request[$language->code . '_name'];
      $SellerInfo->country = $request[$language->code . '_country'];
      $SellerInfo->city = $request[$language->code . '_city'];
      $SellerInfo->state = $request[$language->code . '_state'];
      $SellerInfo->zip_code = $request[$language->code . '_zip_code'];
      $SellerInfo->address = $request[$language->code . '_address'];
      $SellerInfo->details = Purifier::clean($request[$language->code . '_details']);
      $SellerInfo->save();
    }
    Session::flash('success', __('Vendor updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }


  public function sendMail($memb, $package, $paymentMethod, $seller, $bs, $mailType, $replacedPackage = NULL, $removedPackage = NULL)
  {

    if ($mailType != 'admin_removed_current_package' && $mailType != 'admin_removed_next_package') {
      $transaction_id = SellerPermissionHelper::uniqidReal(8);
      $activation = $memb->start_date;
      $expire = $memb->expire_date;
      $info['start_date'] = $activation->toFormattedDateString();
      $info['expire_date'] = $expire->toFormattedDateString();
      $info['payment_method'] = $paymentMethod;
      $lastMemb = $seller->memberships()->orderBy('id', 'DESC')->first();

      $vendorInfo = $this->getVendorDetails($seller->id);

      // process invoice data
      $membershipInvoiceData = [
        'name'      => $vendorInfo->seller_name,
        'username'  => $vendorInfo->username,
        'email'     => $vendorInfo->email,
        'phone'     => $vendorInfo->phone,
        'order_id'  => $transaction_id,
        'base_currency_text_position'  => $bs->base_currency_text_position,
        'base_currency_text'  => $bs->base_currency_text,
        'base_currency_symbol'  => $bs->base_currency_symbol,
        'base_currency_symbol_position'  => $bs->base_currency_symbol_position,
        'amount'  => $package->price,
        'payment_method'  => $paymentMethod ?? null,
        'package_title'  => $package->title,
        'start_date'  => $info['start_date'] ?? $lastMemb->start_date,
        'expire_date'  => $info['expire_date'] ?? $lastMemb->expire_date,
        'website_title'  => $bs->website_title,
        'logo'  => $bs->logo,
      ];

      $file_name = $this->makeInvoice($membershipInvoiceData);
    }

    $mailer = new MegaMailer();
    $data = [
      'toMail' => $seller->email,
      'toName' => $seller->username,
      'username' => $seller->username,
      'website_title' => $bs->website_title,
      'templateType' => $mailType
    ];

    if ($mailType != 'admin_removed_current_package' && $mailType != 'admin_removed_next_package') {
      $data['package_title'] = $package->title;
      $data['package_price'] = ($bs->base_currency_text_position == 'left' ? $bs->base_currency_text . ' ' : '') . $package->price . ($bs->base_currency_text_position == 'right' ? ' ' . $bs->base_currency_text : '');
      $data['activation_date'] = $activation->toFormattedDateString();
      $data['expire_date'] = Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString();
      $data['membership_invoice'] = $file_name;
    }
    if ($mailType != 'admin_removed_current_package' || $mailType != 'admin_removed_next_package') {
      $data['removed_package_title'] = $removedPackage;
    }

    if (!empty($replacedPackage)) {
      $data['replaced_package'] = $replacedPackage;
    }

    $mailer->mailFromAdmin($data);
  }

  public function addCurrPackage(Request $request)
  {
    // Define validation rules
    $validator = Validator::make($request->all(), [
      'package_id' => 'required|exists:packages,id',
      'payment_method' => 'nullable|string',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
      return redirect()->back()
        ->withErrors($validator)
        ->withInput()
        ->with('openModal', true);;
    }
    $seller_id = $request->seller_id;
    $seller = Seller::where('id', $seller_id)->first();
    $bs = Basic::first();

    $selectedPackage = Package::find($request->package_id);

    // calculate expire date for selected package
    if ($selectedPackage->term == 'monthly') {
      $exDate = Carbon::now()->addMonth()->format('d-m-Y');
    } elseif ($selectedPackage->term == 'yearly') {
      $exDate = Carbon::now()->addYear()->format('d-m-Y');
    } elseif ($selectedPackage->term == 'lifetime') {
      $exDate = Carbon::maxValue()->format('d-m-Y');
    }
    // store a new membership for selected package
    $selectedMemb = Membership::create([
      'price' => $selectedPackage->price,
      'currency' => $bs->base_currency_text,
      'currency_symbol' => $bs->base_currency_symbol,
      'payment_method' => $request->payment_method,
      'transaction_id' => uniqid(),
      'status' => 1,
      'receipt' => NULL,
      'transaction_details' => NULL,
      'settings' => null,
      'package_id' => $selectedPackage->id,
      'seller_id' => $seller_id,
      'start_date' => Carbon::parse(Carbon::now()->format('d-m-Y')),
      'expire_date' => Carbon::parse($exDate),
      'is_trial' => 0,
      'trial_days' => 0,
    ]);


    $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $seller, $bs, 'admin_added_current_package');

    Session::flash('success', __('Current Package has been added successfully') . '!');
    return back();
  }


  public function changeCurrPackage(Request $request)
  {
    $seller_id = $request->seller_id;
    $seller = Seller::findOrFail($seller_id);
    $currMembership = SellerPermissionHelper::currMembOrPending($seller_id);
    $nextMembership = SellerPermissionHelper::nextMembership($seller_id);

    $bs = Basic::first();

    $selectedPackage = Package::find($request->package_id);

    // if the vendor has a next package to activate & selected package is 'lifetime' package
    if (!empty($nextMembership) && $selectedPackage->term == 'lifetime') {
      Session::flash('warning', __('To add a Lifetime package as Current Package') . ', ' . __('You have to remove the next package'));
      return back();
    }


    // expire the current package
    $currMembership->expire_date = Carbon::parse(Carbon::now()->subDay()->format('d-m-Y'));
    $currMembership->modified = 1;
    if ($currMembership->status == 0) {
      $currMembership->status = 2;
    }
    $currMembership->save();

    // calculate expire date for selected package
    if ($selectedPackage->term == 'monthly') {
      $exDate = Carbon::now()->addMonth()->format('d-m-Y');
    } elseif ($selectedPackage->term == 'yearly') {
      $exDate = Carbon::now()->addYear()->format('d-m-Y');
    } elseif ($selectedPackage->term == 'lifetime') {
      $exDate = Carbon::maxValue()->format('d-m-Y');
    }
    // store a new membership for selected package
    $selectedMemb = Membership::create([
      'price' => $selectedPackage->price,
      'currency' => $bs->base_currency_text,
      'currency_symbol' => $bs->base_currency_symbol,
      'payment_method' => $request->payment_method,
      'transaction_id' => uniqid(),
      'status' => 1,
      'receipt' => NULL,
      'transaction_details' => NULL,
      'settings' => null,
      'package_id' => $selectedPackage->id,
      'seller_id' => $seller_id,
      'start_date' => Carbon::parse(Carbon::now()->format('d-m-Y')),
      'expire_date' => Carbon::parse($exDate),
      'is_trial' => 0,
      'trial_days' => 0,
    ]);

    // if the user has a next package to activate & selected package is not 'lifetime' package
    if (!empty($nextMembership) && $selectedPackage->term != 'lifetime') {
      $nextPackage = Package::find($nextMembership->package_id);

      // calculate & store next membership's start_date
      $nextMembership->start_date = Carbon::parse(Carbon::parse($exDate)->addDay()->format('d-m-Y'));

      // calculate & store expire date for next membership
      if ($nextPackage->term == 'monthly') {
        $exDate = Carbon::parse(Carbon::parse(Carbon::parse($exDate)->addDay()->format('d-m-Y'))->addMonth()->format('d-m-Y'));
      } elseif ($nextPackage->term == 'yearly') {
        $exDate = Carbon::parse(Carbon::parse(Carbon::parse($exDate)->addDay()->format('d-m-Y'))->addYear()->format('d-m-Y'));
      } else {
        $exDate = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
      }
      $nextMembership->expire_date = $exDate;
      $nextMembership->save();
    }

    $currentPackage = Package::select('title')->findOrFail($currMembership->package_id);
    $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $seller, $bs, 'admin_changed_current_package', $currentPackage->title);


    Session::flash('success', __('Current Package changed successfully') . '!');
    return back();
  }

  public function removeCurrPackage(Request $request)
  {
    $seller_id = $request->seller_id;
    $seller = Seller::where('id', $seller_id)->firstOrFail();
    $currMembership = SellerPermissionHelper::currMembOrPending($seller_id);
    $currPackage = Package::select('title')->findOrFail($currMembership->package_id);
    $nextMembership = SellerPermissionHelper::nextMembership($seller_id);
    $bs = Basic::first();

    $today = Carbon::now();

    // just expire the current package
    $currMembership->expire_date = $today->subDay();
    $currMembership->modified = 1;
    if ($currMembership->status == 0) {
      $currMembership->status = 2;
    }
    $currMembership->save();

    // if next package exists
    if (!empty($nextMembership)) {
      $nextPackage = Package::find($nextMembership->package_id);

      $nextMembership->start_date = Carbon::parse(Carbon::today()->format('d-m-Y'));
      if ($nextPackage->term == 'monthly') {
        $nextMembership->expire_date = Carbon::parse(Carbon::today()->addMonth()->format('d-m-Y'));
      } elseif ($nextPackage->term == 'yearly') {
        $nextMembership->expire_date = Carbon::parse(Carbon::today()->addYear()->format('d-m-Y'));
      } elseif ($nextPackage->term == 'lifetime') {
        $nextMembership->expire_date = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
      }
      $nextMembership->save();
    }

    $this->sendMail(NULL, NULL, $request->payment_method, $seller, $bs, 'admin_removed_current_package', NULL, $currPackage->title);

    Session::flash('success', __('Current Package removed successfully') . '!');
    return back();
  }

  public function addNextPackage(Request $request)
  {
    $seller_id = $request->seller_id;
    $actionValue = array('change', 'remove', 'approve', 'reject');

    $hasPendingMemb = SellerPermissionHelper::hasPendingMembership($seller_id);
    if ($hasPendingMemb) {
      Session::flash('warning', __('This user already has a pending package') . '. ' . __('Please take an action') . '( ' . implode('/ ', __($actionValue)) . ' ) ' . __('for that package first') . '.');
      return back();
    }

    $currMembership = SellerPermissionHelper::userPackage($seller_id);
    $currPackage = Package::find($currMembership->package_id);
    $seller = Seller::where('id', $seller_id)->first();
    $bs = Basic::first();

    $selectedPackage = Package::find($request->package_id);
    $actionValueTwo = array('change', 'remove');

    if ($currMembership->is_trial == 1) {
      Session::flash('warning', __('If your current package is trial package') . ', ' . __('then you have to') . ' ' . implode(' / ', __($actionValueTwo)) . ' ' . __('the current package first') . '.');
      return back();
    }


    // if current package is not lifetime package
    if ($currPackage->term != 'lifetime') {
      // calculate expire date for selected package
      if ($selectedPackage->term == 'monthly') {
        $exDate = Carbon::parse($currMembership->expire_date)->addDay()->addMonth()->format('d-m-Y');
      } elseif ($selectedPackage->term == 'yearly') {
        $exDate = Carbon::parse($currMembership->expire_date)->addDay()->addYear()->format('d-m-Y');
      } elseif ($selectedPackage->term == 'lifetime') {
        $exDate = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
      }
      // store a new membership for selected package
      $selectedMemb = Membership::create([
        'price' => $selectedPackage->price,
        'currency' => $bs->base_currency_text,
        'currency_symbol' => $bs->base_currency_symbol,
        'payment_method' => $request->payment_method,
        'transaction_id' => uniqid(),
        'status' => 1,
        'receipt' => NULL,
        'transaction_details' => NULL,
        'settings' => null,
        'package_id' => $selectedPackage->id,
        'seller_id' => $seller_id,
        'start_date' => Carbon::parse(Carbon::parse($currMembership->expire_date)->addDay()->format('d-m-Y')),
        'expire_date' => Carbon::parse($exDate),
        'is_trial' => 0,
        'trial_days' => 0,
      ]);

      $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $seller, $bs, 'admin_added_next_package');
    } else {
      Session::flash('warning', __('If your current package is lifetime package') . ', ' . __('then you have to') . ' ' . implode(' / ', __($actionValueTwo)) . ' ' . __('the current package first') . '.');

      return back();
    }


    Session::flash('success', __('Next Package has been added successfully') . '!');
    return back();
  }

  public function changeNextPackage(Request $request)
  {

    $seller_id = $request->seller_id;
    $seller = Seller::where('id', $seller_id)->first();
    $bs = Basic::first();
    $nextMembership = SellerPermissionHelper::nextMembership($seller_id);
    $nextPackage = Package::find($nextMembership->package_id);
    $selectedPackage = Package::find($request->package_id);

    $prevStartDate = $nextMembership->start_date;
    // set the start_date to unlimited
    $nextMembership->start_date = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
    $nextMembership->modified = 1;
    $nextMembership->save();

    // calculate expire date for selected package
    if ($selectedPackage->term == 'monthly') {
      $exDate = Carbon::parse($prevStartDate)->addMonth()->format('d-m-Y');
    } elseif ($selectedPackage->term == 'yearly') {
      $exDate = Carbon::parse($prevStartDate)->addYear()->format('d-m-Y');
    } elseif ($selectedPackage->term == 'lifetime') {
      $exDate = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
    }

    // store a new membership for selected package
    $selectedMemb = Membership::create([
      'price' => $selectedPackage->price,
      'currency' => $bs->base_currency_text,
      'currency_symbol' => $bs->base_currency_symbol,
      'payment_method' => $request->payment_method,
      'transaction_id' => uniqid(),
      'status' => 1,
      'receipt' => NULL,
      'transaction_details' => NULL,
      'settings' => json_encode($bs),
      'package_id' => $selectedPackage->id,
      'seller_id' => $seller_id,
      'start_date' => Carbon::parse($prevStartDate),
      'expire_date' => Carbon::parse($exDate),
      'is_trial' => 0,
      'trial_days' => 0,
    ]);

    $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $seller, $bs, 'admin_changed_next_package', $nextPackage->title);

    Session::flash('success', __('Next Package changed successfully') . '!');
    return back();
  }

  public function removeNextPackage(Request $request)
  {
    $seller_id = $request->seller_id;
    $seller = Seller::where('id', $seller_id)->first();
    $bs = Basic::first();
    $nextMembership = SellerPermissionHelper::nextMembership($seller_id);
    // set the start_date to unlimited
    $nextMembership->start_date = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
    $nextMembership->modified = 1;
    $nextMembership->save();

    $nextPackage = Package::select('title')->findOrFail($nextMembership->package_id);


    $this->sendMail(NULL, NULL, $request->payment_method, $seller, $bs, 'admin_removed_next_package', NULL, $nextPackage->title);

    Session::flash('success', __('Next Package removed successfully') . '!');
    return back();
  }

  //secrtet login
  public function secret_login($id)
  {
    Session::put('secret_login', 1);
    $seller = Seller::where('id', $id)->first();
    Auth::guard('seller')->login($seller);
    return redirect()->route('vendor.dashboard');
  }

  public function destroy($id)
  {

    $this->deleteVendor($id);

    return redirect()->back()->with('success', __('Vendor information deleted successfully') . '!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;
    foreach ($ids as $id) {
      $this->deleteVendor($id);
    }
    Session::flash('success', __('Vendors are deleted successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function deleteVendor($id)
  {

    $seller = Seller::findOrFail($id);
    // vendor  memeberships
    $memberships = $seller->memberships()->get();


    foreach ($memberships as $membership) {
      @unlink(public_path('assets/front/img/membership/receipt/') . $membership->receipt);
      $membership->delete();
    }
    //vendor infos
    $seller_infos = $seller->seller_infos()->get();
    foreach ($seller_infos as $info) {
      $info->delete();
    }
    //delete vendor space and it's related
    $spaces = Space::where('seller_id', $id)->get();

    foreach ($spaces as $space) {
      // delete the thumbnail image
      $thumbnailImagePath = !empty($space->thumbnail_image) ? public_path('assets/img/spaces/thumbnail-images/' . $space->thumbnail_image) : null;
      if (!empty($thumbnailImagePath) && file_exists($thumbnailImagePath)) {
        @unlink($thumbnailImagePath);
      }

      // delete the slider images
      $sliderImages = json_decode($space->slider_images);

      foreach ($sliderImages as $sliderImage) {
        $sliderImagePath = !empty($sliderImage) ? public_path('assets/img/spaces/slider-images/' . $sliderImage) : null;
        if (!empty($sliderImagePath) && file_exists($sliderImagePath)) {
          @unlink($sliderImagePath);
        }
      }

      // delete all the service-contents
      $spaceContents = $space->spaceContents()->get();


      foreach ($spaceContents as $spaceContent) {
        $spaceContent->delete();
      }
      // delete all the service
      $services = $space->spaceService()->get();
      if ($services->isNotEmpty()) {
        foreach ($services as $service) {
          // delete space service content
          $serviceContents = SpaceServiceContent::where('space_service_id', $service->id)->get();
          if ($serviceContents->isNotEmpty()) {
            foreach ($serviceContents as $serviceContent) {
              $serviceContent->delete();
            }
          }

          // delete space sub-service
          $subservices = SubService::where('service_id', $service->id)->get();
          if ($subservices->isNotEmpty()) {
            foreach ($subservices as $subservice) {
              $subserviceContents = SubServiceContent::where('sub_service_id', $subservice->id)->get();
              if ($subserviceContents->isNotEmpty()) {
                foreach ($subserviceContents as $subserviceContent) {
                  $subserviceContent->delete();
                }
              }

              // delete the sub-service image
              $subserviceImagePath = !empty($subservice->image) ? public_path('assets/img/sub-services/' . $subservice->image) : null;
              if (!empty($subserviceImagePath) && file_exists($subserviceImagePath)) {
                @unlink($subserviceImagePath);
              }
              $subservice->delete();
            }
          }
          // delete the thumbnail image
          $serviceImagePath = !empty($service->image) ? public_path('assets/img/space-service/' . $service->image) : null;
          if (!empty($serviceImagePath) && file_exists($serviceImagePath)) {
            @unlink($thumbnailImagePath);
          }
          $service->delete();
        }
      }

      // delete all the orders of this service
      $bookings = SpaceBooking::where('space_id', $space->id)->get();

      if (count($bookings) > 0) {
        foreach ($bookings as $booking) {
          $serviceStageInfo = json_decode($booking->service_stage_info);
          if (!is_null($serviceStageInfo)) {
            foreach ($serviceStageInfo as $information) {
              @unlink(public_path('assets/img/space-service/' . $information->img));
            }
          }

          // delete booking receipt
          @unlink(public_path('assets/img/attachments/space/' . $booking->receipt));

          // delete booking invoice
          @unlink(public_path('assets/file/invoices/space/' . $booking->invoice));
          $booking->delete();
        }
      }
      // delete all the reviews of this service
      $reviews = $space->review()->get();


      if (count($reviews) > 0) {
        foreach ($reviews as $review) {
          $review->delete();
        }
      }

      // delete wishlist records of this service
      $records = $space->wishlist()->get();

      if (count($records) > 0) {
        foreach ($records as $record) {
          $record->delete();
        }
      }

      $space->delete();
    }

    // delete global days according to vendor
    $globalDays = GlobalDay::where('seller_id', $id)->get();
    if ($globalDays->isNotEmpty()) {
      foreach ($globalDays as $globalDay) {
        $globalDay->delete();
      }
    }


    // delete time slot according to vendor
    $timeSlots = TimeSlot::where('seller_id', $id)->get();
    if ($timeSlots->isNotEmpty()) {
      foreach ($timeSlots as $timeSlot) {
        $timeSlot->delete();
      }
    }
    // delete space feature according to vendor
    $spaceFeatures = SpaceFeature::where('seller_id', $id)->get();
    if ($spaceFeatures->isNotEmpty()) {
      foreach ($spaceFeatures as $spaceFeature) {
        $spaceFeature->delete();
      }
    }


    // delete all the support tickets of this seller
    $tickets = SupportTicket::where([['user_id', $seller->id], ['user_type', 'seller']])->get();
    if (count($tickets) > 0) {
      foreach ($tickets as $ticket) {
        // delete all the conversations of each ticket
        $conversations = $ticket->conversation()->get();

        if (count($conversations) > 0) {
          foreach ($conversations as $conversation) {
            // delete attachment of this conversation
            @unlink(public_path('assets/file/ticket-files/' . $conversation->attachment));

            // delete conversation
            $conversation->delete();
          }
        }


        // delete attachment of this ticket
        @unlink(public_path('assets/file/ticket-files/' . $ticket->attachment));

        // delete ticket
        $ticket->delete();
      }
    }
    //delete support tickets end

    // delete withdraw requests
    $withdraw_requests = Withdraw::where('seller_id', $seller->id)->get();
    foreach ($withdraw_requests as $withdraw_request) {
      $withdraw_request->delete();
    }
    // delete withdraw requests end

    //finally delete the seller
    @unlink(public_path('assets/admin/img/seller-photo/') . $seller->photo);
    $seller->delete();
  }

  //update_seller_balance
  public function update_seller_balance(Request $request, $id)
  {
    $rules = [
      'amount_status' => 'required',
      'amount' => 'required|numeric',
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }
    $seller = Seller::where('id', $id)->firstOrFail();
    // get the website title info from db
    $website_info = Basic::select('website_title', 'smtp_status', 'base_currency_text_position', 'base_currency_text', 'base_currency_symbol', 'base_currency_symbol_position')->first();
    //add or subtract seller balance
    if ($request->amount_status == 1) {
      Transaction::create([
        'transcation_id' => time(),
        'order_number' => null,
        'transcation_type' => 3,
        'user_id' => null,
        'seller_id' => $seller->id,
        'payment_status' => 'completed',
        'payment_method' => null,
        'grand_total' => $request->amount,
        'pre_balance' => $seller->amount != 0 ? $seller->amount : 0.00,
        'after_balance' => $seller->amount + $request->amount,
        'gateway_type' => null,
        'currency_symbol' => $website_info->base_currency_symbol,
        'currency_symbol_position' => $website_info->base_currency_symbol_position,
      ]);

      $new_seller_amount = $seller->amount + $request->amount;
    } else {
      Transaction::create([
        'transcation_id' => time(),
        'order_number' => null,
        'transcation_type' => 4,
        'user_id' => null,
        'seller_id' => $seller->id,
        'payment_status' => 'completed',
        'payment_method' => null,
        'grand_total' => $request->amount,
        'pre_balance' => $seller->amount != 0 ? $seller->amount : 0.00,
        'after_balance' => $seller->amount - $request->amount,
        'gateway_type' => null,
        'currency_symbol' => $website_info->base_currency_symbol,
        'currency_symbol_position' => $website_info->base_currency_symbol_position,
      ]);
      $new_seller_amount = $seller->amount - $request->amount;
    }

    //send mail
    if ($request->amount_status == 1) {
      $template_type = 'balance_add';

      $seller_alert_msg = __('Balance added to vendor account successfully') . '!';
    } else {
      $template_type = 'balance_subtract';
      $seller_alert_msg = __('Balance Subtract from vendor account successfully') . '!';
    }
    //mail sending


    $new_mail_seller_amount = $website_info->base_currency_text_position == 'left' ? $website_info->base_currency_text . ' ' . $new_seller_amount : $new_seller_amount . ' ' . $website_info->base_currency_text;

    $mail_amount = $website_info->base_currency_text_position == 'left' ? $website_info->base_currency_text . ' ' . $request->amount : $request->amount . ' ' . $website_info->base_currency_text;

    $websiteTitle = $website_info->website_title;

    // initialize a new mail
    if ($website_info->smtp_status == 1) {
      $mail = new MegaMailer();
      $amount = $mail_amount;
      $data = [
        'templateType' => $template_type,
        'username' => $seller->username,
        'amount' => $amount,
        'current_balance' => $new_mail_seller_amount,
        'toMail' => $seller->email,
        'website_title' => $websiteTitle,
      ];
      $mail->mailFromAdmin($data);
    }
    //mail sending end
    $seller->amount = $new_seller_amount;
    $seller->save();
    Session::flash('success', $seller_alert_msg);

    return Response::json(['status' => 'success'], 200);
  }

  protected function getVendorDetails($sellerId)
  {
    return Seller::select('sellers.*', 'seller_infos.name as seller_name')
      ->leftJoin('seller_infos', function ($join) use ($sellerId) {
        $join->on('sellers.id', '=', 'seller_infos.seller_id')
          ->where('sellers.id', $sellerId);
      })
      ->where('sellers.id', '=', $sellerId)
      ->first();
  }
}
