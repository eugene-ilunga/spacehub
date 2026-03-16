<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\Space;
use App\Models\SpaceBooking;
use App\Models\SpaceService;
use App\Models\SpaceServiceContent;
use App\Models\SubService;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\UploadFile;
use App\Http\Helpers\BasicMailer;
use App\Models\BasicSettings\Basic;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\Models\BasicSettings\MailTemplate;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\ForgetPasswordRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Models\Admin;
use App\Models\TimeSlot;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
  public function login()
  {

    $pageType = null;
    if (request()->input('redirectPath') == 'space-details') {
      $url = url()->previous();
      if ($url != route('user.login')) {
        Session::put('redirectTo', $url);
      }
    } elseif (request()->input('redirectPath') == 'checkout') {
      $url = route('frontend.booking.checkout.index');
      $pageType = 'space';
      if ($url != route('user.login')) {
        Session::put('redirectTo', $url);
      }
    } elseif(request()->input('redirect_path') == 'product_checkout'){
      $url = route('shop.checkout');
      $pageType = 'product';
      if ($url != route('user.login')) {
        Session::put('redirectTo', $url);
    }
  }
    
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();


    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_customer_login as meta_keywords', 'meta_description_customer_login as meta_description')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();
    $queryResult['direction'] = $language->direction;

    $queryResult['bs'] = Basic::query()->select('google_recaptcha_status', 'facebook_login_status', 'google_login_status')->first();
    $queryResult['pageType'] = $pageType;

    return view('frontend.login', $queryResult);
  }

  public function redirectToFacebook()
  {
    return Socialite::driver('facebook')->redirect();
  }

  public function handleFacebookCallback()
  {
    return $this->authenticationViaProvider('facebook');
  }

  public function redirectToGoogle()
  {
    return Socialite::driver('google')->redirect();
  }

  public function handleGoogleCallback()
  {
    return $this->authenticationViaProvider('google');
  }

  public function authenticationViaProvider($driver)
  {
    // get the url from session which will be redirect after login
    if (Session::has('redirectTo')) {
      $redirectURL = Session::get('redirectTo');
    } else {
      $redirectURL = route('user.dashboard');
    }

    $responseData = Socialite::driver($driver)->user();
    $userInfo = $responseData->user;

    $isUser = User::query()->where('email_address', '=', $userInfo['email'])->first();

    if (!empty($isUser)) {
      // log in
      if ($isUser->status == 1) {
        Auth::login($isUser);

        return redirect($redirectURL);
      } else {
        Session::flash('error', __('Sorry, your account has been deactivated') . '.');

        return redirect()->route('user.login');
      }
    } else {
      // get user avatar and save it
      $avatar = $responseData->getAvatar();
      $fileContents = file_get_contents($avatar);

      $avatarName = $responseData->getId() . '.jpg';
      $path = public_path('assets/img/users/');

      file_put_contents($path . $avatarName, $fileContents);

      // sign up
      $user = new User();

      if ($driver == 'facebook') {
        $user->first_name = $userInfo['name'];
      } else {
        $user->first_name = $userInfo['given_name'];
        $user->last_name = $userInfo['family_name'];
      }

      $user->image = $avatarName;
      $user->email_address = $userInfo['email'];
      $user->email_verified_at = date('Y-m-d H:i:s');
      $user->status = 1;
      $user->provider = ($driver == 'facebook') ? 'facebook' : 'google';
      $user->provider_id = $userInfo['id'];
      $user->save();

      Auth::login($user);

      return redirect($redirectURL);
    }
  }

  public function loginSubmit(Request $request)
  {

    $google_recaptcha_status = Basic::value('google_recaptcha_status');
    $request->validate([
      'username' => 'required',
      'password' => 'required',
      'g-recaptcha-response' => [
        Rule::requiredIf($google_recaptcha_status == 1),
      ],
    ]);

    // get the url from session which will be redirect after login

    if ($request->session()->has('redirectTo')) {
      $redirectURL = $request->session()->get('redirectTo');
    } else {
      $redirectURL = route('user.dashboard');
    }


    // get the email-address and password which has provided by the user
    $credentials = $request->only('username', 'password');

    // login attempt
    if (Auth::guard('web')->attempt($credentials)) {

      $authUser = Auth::guard('web')->user();

      // first, check whether the user's email address verified or not
      if (is_null($authUser->email_verified_at)) {
        $request->session()->flash('error', __('Please, verify your email address') . '.');

        // logout auth user as condition not satisfied
        Auth::guard('web')->logout();

        return redirect()->back();
      }

      // second, check whether the user's account is active or not
      if ($authUser->status == 0) {
        $request->session()->flash('error', __('Sorry, your account has been deactivated') . '.');

        // logout auth user as condition not satisfied
        Auth::guard('web')->logout();

        return redirect()->back();
      }

      // before, redirect to next url forget the session value
      if ($request->session()->has('redirectTo')) {
        $request->session()->forget('redirectTo');
      }
      $formData = [];
      if ($request->session()->has('checkout_info')) {
        $formData = $request->session()->get('checkout_info');
      }
      // otherwise, redirect auth user to next url
      return redirect($redirectURL)->withInput($formData);
    } else {
      $request->session()->flash('error', __('Incorrect username or password') . '!');
      return redirect()->back();
    }
  }

  public function forgetPassword()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_customer_forget_password as meta_keywords', 'meta_description_customer_forget_password as meta_description')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    return view('frontend.forget-password', $queryResult);
  }

  public function forgetPasswordMail(ForgetPasswordRequest $request)
  {
    $user = User::query()->where('email_address', '=', $request->email_address)->first();

    // store user email in session to use it later
    $request->session()->put('userEmail', $user->email_address);

    // get the mail template information from db
    $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'reset_password')->first();
    $mailData['subject'] = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // get the website title info from db
    $websiteTitle = Basic::query()->pluck('website_title')->first();

    $name = $user->first_name . ' ' . $user->last_name;

    $link = '<a href="' . url("user/reset-password") . '">' . __('Click Here') . '</a>';


    $mailBody = str_replace('{customer_name}', $name, $mailBody);
    $mailBody = str_replace('{password_reset_link}', $link, $mailBody);
    $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);

    $mailData['body'] = $mailBody;

    $mailData['recipient'] = $user->email_address;

    $mailData['sessionMessage'] = __('A mail has been sent to your email address') . '.';

    BasicMailer::sendMail($mailData);

    return redirect()->back();
  }

  public function resetPassword()
  {
    $misc = new MiscellaneousController();

    $breadcrumb = $misc->getBreadcrumb();

    return view('frontend.reset-password', compact('breadcrumb'));
  }

  public function resetPasswordSubmit(ResetPasswordRequest $request)
  {
    if ($request->session()->has('userEmail')) {
      // get the user email from session
      $emailAddress = $request->session()->get('userEmail');

      $user = User::query()->where('email_address', '=', $emailAddress)->first();

      $user->update([
        'password' => Hash::make($request->new_password)
      ]);

      $request->session()->flash('success', __('Password updated successfully') . '.');
    } else {
      $request->session()->flash('error', __('Something went wrong') . '!');
    }

    return redirect()->route('user.login');
  }

  public function signup()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_customer_signup as meta_keywords', 'meta_description_customer_signup as meta_description')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $queryResult['recaptchaStatus'] = Basic::query()->pluck('google_recaptcha_status')->first();
    $queryResult['direction'] = $language->direction;

    return view('frontend.signup', $queryResult);
  }

  public function signupSubmit(Request $request)
  {

    $websiteTitle = Basic::query()->pluck('website_title')->first();
    $google_recaptcha_status = Basic::value('google_recaptcha_status');
 

    $request->validate([
      'user_name' => 'required|string|max:50',
      'email_address' => 'required|email|unique:users,email_address',
      'password' => 'required|string|min:8|confirmed',
      'g-recaptcha-response' => [
        Rule::requiredIf($google_recaptcha_status == 1),
      ],
    ]);
  
    $user = new User();
    $user->username = $request->user_name;
    $user->email_address = $request->email_address;
    $user->password = Hash::make($request->password);

    // first, generate a random string
    $randStr = Str::random(20);

    // second, generate a token
    $token = md5($randStr . $request->username . $request->email);

    $user->verification_token = $token;
    $user->save();

    /**
     * prepare a verification mail and, send it to user to verify his/her email address,
     * get the mail template information from db
     */
    $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'verify_email')->first();
    $mailData['subject'] = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    $link = '<a href="' . url("user/signup-verify/" . $token) . '">' . __('Click Here') . '</a>';


    $mailBody = str_replace('{username}', $request->username, $mailBody);
    $mailBody = str_replace('{verification_link}', $link, $mailBody);
    $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);

    $mailData['body'] = $mailBody;

    $mailData['recipient'] = $request->email_address;

    $mailData['sessionMessage'] = __('A verification link has been sent to your email address') . '.';

    BasicMailer::sendMail($mailData);

    return redirect()->back();
  }

  public function signupVerify(Request $request, $token)
  {
    try {
      $user = User::query()->where('verification_token', '=', $token)->firstOrFail();

      // after verify user email, put "null" in the "verification token"
      $user->update([
        'email_verified_at' => date('Y-m-d H:i:s'),
        'status' => 1,
        'verification_token' => null
      ]);

      $request->session()->flash('success', __('Your email address has been verified') . '.');

      // after email verification, authenticate this user
      Auth::guard('web')->login($user);

      return redirect()->route('user.dashboard');
    } catch (ModelNotFoundException $e) {
      $request->session()->flash('error', __('Could not verify your email address') . '!');

      return redirect()->route('user.signup');
    }
  }

  public function redirectToDashboard()
  {
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $user = Auth::guard('web')->user();

    $queryResult['authUser'] = $user;

    $queryResult['numOfSpaceBooking'] = $user->spaceBooking()->count();
    $queryResult['bookingDetails'] = SpaceBooking::query()
      ->where('user_id', '=', $user->id)
      ->select('space_bookings.id', 'space_bookings.booking_number', 'space_bookings.created_at', 'space_bookings.booking_status')
      ->orderByDesc('space_bookings.id')
      ->get();

    $queryResult['totalRevenue'] = round($user->spaceBooking()->sum('grand_total'));
    $queryResult['spaceReview'] = $user->spaceReview()->count();

    $queryResult['numOfWishlistedServices'] = $user->spaceWishlisted()->count();

    return view('frontend.user.profile.dashboard', $queryResult);
  }


  public function editProfile()
  {
    $misc = new MiscellaneousController();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $queryResult['authUser'] = Auth::guard('web')->user();


    return view('frontend.user.profile.edit-profile', $queryResult);
  }

  public function updateProfile(Request $request)
  {
    // Define validation rules
    $rules = [
      'username' => [
        'required',
        'string',
        'max:255',
        'unique:users,username,' . Auth::guard('web')->id()
      ],
    ];

    // Validate the request data
    $request->validate($rules);

    $authUser = Auth::guard('web')->user();

    if ($request->hasFile('image')) {
      $image = $request->file('image');

      // 1. Validate MIME type using your custom rule
      $mimeRule = new ImageMimeTypeRule();
      if (!$mimeRule->passes('image', $image)) {
        return redirect()->back()
          ->withErrors(['image' => $mimeRule->message()])
          ->withInput();
      }

      // 2. Manually validate dimensions
      try {
        list($width, $height) = getimagesize($image->getPathname());

        if ($width !== 80 || $height !== 80) {
          return redirect()->back()
            ->withErrors(['image' => __('The image must be exactly 80x80 pixels') . '.'])
            ->withInput();
        }
      } catch (\Exception $e) {
        return redirect()->back()
          ->withErrors(['image' => __('Could not read image dimensions') . '.'])
          ->withInput();
      }

      // Process the upload
      $uploadPath = 'assets/img/users/';

      if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0755, true);
      }

      $imageName = UploadFile::update($uploadPath, $image, $authUser->image);
      $authUser->image = $imageName;
    }

    $authUser->update($request->except('image') + [
      'image' => $request->hasFile('image') ? $imageName : $authUser->image
    ]);

    $request->session()->flash('success', __('Your profile has been updated successfully') . '.');

    return redirect()->back();
  }

  public function changePassword()
  {
    $misc = new MiscellaneousController();

    $breadcrumb = $misc->getBreadcrumb();

    return view('frontend.user.profile.change-password', compact('breadcrumb'));
  }

  public function updatePassword(UpdatePasswordRequest $request)
  {
    $user = Auth::guard('web')->user();
    $user->update([
      'password' => Hash::make($request->new_password)
    ]);
    $request->session()->flash('success', __('Password updated successfully') . '.');

    return redirect()->back();
  }

  public function spaceBooking()
  {
    $misc = new MiscellaneousController();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();
    $language = $misc->getLanguage();
    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $user = Auth::guard('web')->user();

    $queryResult['bookingDetails'] = SpaceBooking::query()
      ->where('user_id', '=', $user->id)
      ->select('space_bookings.id', 'space_bookings.booking_number', 'space_bookings.created_at', 'space_bookings.booking_status')
      ->orderBy('space_bookings.id', 'DESC')
      ->get();


    return view('frontend.user.space-info.my-booking', $queryResult);
  }


  public function spaceBookingDetails($id)
  {
    $misc = new MiscellaneousController();
    $data['breadcrumb'] = $misc->getBreadcrumb();
    $language = $misc->getLanguage();
    $data['pageHeading'] = $misc->getPageHeading($language);
    $user = Auth::guard('web')->user();
    $data['user'] = $user;

    $booking = SpaceBooking::query()->where('id', '=', $id)->firstOrFail();
    $space = $booking->space()->first();

    $data['space_type'] = $space->space_type;
    if ($space->space_type == 3) {
      $data['rent'] = $space->price_per_day;
      $data['type'] = __('Day');
    } else if ($space->space_type == 2) {
      $data['rent'] = $space->rent_per_hour;
      $data['type'] = __('Hour');
    } else {
      $timeSlotId = $booking->time_slot_id;
      $timeSlotInfo = TimeSlot::query()->select('time_slot_rent')->where('id', '=', $timeSlotId)->first();
      if ($space->use_slot_rent == 1) {
        $data['rent'] = $timeSlotInfo->time_slot_rent ?? 0.00;
        $data['type'] = null;
      } else {
        $data['rent'] = $space->space_rent ?? 0.00;
        $data['type'] = null;
      }
    }

    $stageServices = json_decode($booking->service_stage_info, true);
    $otherServices = json_decode($booking->other_service_info, true);

    $services = [];
    if (is_array($stageServices)) {
      $services = array_merge($services, $stageServices);
    }
    if (is_array($otherServices)) {
      $services = array_merge($services, $otherServices);
    }

    $spaceServiceMap = [];
    foreach ($services as $item) {
      $spaceServiceId = $item['spaceServiceId'];

      if (!isset($spaceServiceMap[$spaceServiceId])) {
        $spaceService = SpaceService::with([
          'serviceContents' => function ($query) use ($language) {
            $query->where('language_id', $language->id)
              ->select('space_service_id', 'title', 'slug', 'language_id');
          }
        ])->find($item['spaceServiceId']);


        if (!is_null($spaceService)) {
          $spaceService->number_of_custom_day = $item['numberOfCustomDay'] ?? null;
          $spaceService->number_of_guest = $booking['number_of_guest'] ?? null;
          $spaceService->total_hour = $booking['total_hour'] ?? null;
          $spaceService->space_type = $space->space_type ?? null;

          $serviceTitle = SpaceServiceContent::select('title as serviceTitle', 'space_service_id')->where([
            ['space_service_id', $spaceServiceId],
            ['language_id', $language->id],
          ])->first();

          if ($serviceTitle) {
            $spaceService->service_title = $serviceTitle->serviceTitle;
          }
        }

        $spaceServiceMap[$spaceServiceId] = [
          'spaceService' => $spaceService,
          'subServices' => [],
          'global_service_total_price' => 0,
        ];
      }

      if (array_key_exists('subServiceId', $item)) {
        $subService = SubService::with([
          'subServiceContents' => function ($query) use ($language) {
            $query->where('language_id', $language->id)
              ->select('sub_service_id', 'title', 'slug', 'language_id');
          }
        ])->find($item['subServiceId']);

        $subService->price_type = $spaceService->price_type ?? null;

        if ($space->space_type == 3) {
          $subService->number_of_custom_day = $item['numberOfCustomDay'] ?? 1;
        } elseif ($space->space_type == 2) {
          $subService->total_hour = $spaceService->total_hour ?? 1;
        }
        $subService->number_of_guest = $booking['number_of_guest'] ?? 1;

        if ($subService) {
          $subService->sub_service_title = $subService->subServiceContents->first()->title;

          if ($spaceService['has_sub_services'] == 1) {
            if ($spaceService['price_type'] == 'fixed') {
              if ($spaceService['space_type'] == 3) {
                $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $subService->price * $subService['number_of_custom_day'];
              } elseif ($spaceService['space_type'] == 2) {
                $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $subService->price * $subService['total_hour'];
              } else {
                $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $subService->price;
              }
            } else {
              if ($spaceService['space_type'] == 3) {
                $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $subService->price * $subService['number_of_custom_day'] * $subService['number_of_guest'];
              } elseif ($spaceService['space_type'] == 2) {
                $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $subService->price * $subService['total_hour'] * $subService['number_of_guest'];
              } else {
                $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $subService->price * $subService['number_of_guest'];
              }
            }
          }
        }
        $spaceServiceMap[$spaceServiceId]['subServices'][] = $subService;
      }

      if ($spaceService['has_sub_services'] != 1) {
        if ($spaceService['price_type'] == 'fixed') {
          if ($spaceService['space_type'] == 3) {
            $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $spaceService['price'] * $spaceService['number_of_custom_day'];
          } elseif ($spaceService['space_type'] == 2) {
            $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $spaceService['price'] * $spaceService['total_hour'];
          } else {
            $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $spaceService['price'];
          }
        }
        else{
          if ($spaceService['space_type'] == 3) {
            $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $spaceService['price'] * $spaceService['number_of_custom_day'] * $spaceService['number_of_guest'];
          } elseif ($spaceService['space_type'] == 2) {
            $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $spaceService['price'] * $spaceService['total_hour'] * $spaceService['number_of_guest'];
          } else {
            $spaceServiceMap[$spaceServiceId]['global_service_total_price'] += $spaceService['price'] * $spaceService['number_of_guest'];
          }

        }
      }
    }

    $services = array_values($spaceServiceMap);
    $data['services'] = $services;
    
    $data['basic'] = Basic::select('base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position')->first();

    $data['spaceContent'] = $space->spaceContents()->where('language_id', $language->id)->select('title', 'slug', 'space_id', 'address')->first();

    $data['bookingInfo'] = $booking;
    $basicInfo = Basic::query()->select('admin_profile')->first();

    if (!empty($booking) &&  is_null($booking->seller_id)) {
      $data['seller'] = Admin::where([
        ['role_id', null],
        ['username', 'admin'],
      ])->first();

      if ($data['seller']) {
        $data['seller']->url = ($basicInfo->admin_profile == 1)
          ? route('frontend.seller.details', ['username' => $data['seller']->username, 'admin' => true])
          : '#';
      }
    } else {
      $data['seller'] = Seller::select('sellers.*', 'seller_infos.address')
        ->leftJoin('seller_infos', 'sellers.id', '=', 'seller_infos.seller_id')
        ->where('sellers.id', $booking->seller_id)
        ->first();
      if ($data['seller']) {
        $data['seller']->url = route('frontend.seller.details', ['username' => $data['seller']->username]);
      }
    }
    return view('frontend.user.space-info.space-booking-details', $data);
  }

  public function spaceWishlist()
  {
    $misc = new MiscellaneousController();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $authUser = Auth::guard('web')->user();

    $listedServices = $authUser->spaceWishlisted()->orderByDesc('id')->get();

    $language = $misc->getLanguage();
    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $listedServices->map(function ($listedService) use ($language) {
      $service = Space::query()->find($listedService->space_id);

      $listedService['serviceContent'] = $service->spaceContents()->where('language_id', $language->id)->first();
    });

    $queryResult['listedServices'] = $listedServices;

    return view('frontend.user.space-info.space-wishlist', $queryResult);
  }

  public function removeSpaecWishlisted($space_id)
  {
    try {
      $user = Auth::guard('web')->user();

      $listedSpace = $user->spaceWishlisted()->where('space_id', $space_id)->firstOrFail();

      $listedSpace->delete();

      return redirect()->back()->with('success', __('Space has been removed from wishlist') . '.');
    } catch (ModelNotFoundException $e) {
      return redirect()->back()->with('error', __('Space not found') . '!');
    }
  }

  public function logoutSubmit(Request $request)
  {
    Auth::guard('web')->logout();

    if ($request->session()->has('redirectTo')) {
      $request->session()->forget('redirectTo');
    }

    return redirect()->route('user.login');
  }
}
