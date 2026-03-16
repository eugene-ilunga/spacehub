<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class MiscellaneousController extends Controller
{

  public function getLanguage()
  {
    // get the current locale of this system
    $locale = null;
    if (Session::has('currentLocaleCode')) {
      $locale = Session::get('currentLocaleCode');
    }

    if (empty($locale)) {
      $language = Language::query()->where('is_default', '=', 1)->first();
    } else {
      $language = Language::query()->where('code', '=', $locale)->first();
    }

    if (!$language) {
      return new Language; 
    }

    return $language;
  }


  public function storeSubscriber(Request $request)
  {
    
    $rules = [
      'email_id' => 'required|email:rfc,dns|unique:subscribers'
    ];

    $messages = [
      'email_id.required' => __('Please enter your email address') . '.',
      'email_id.unique' => __('This email address is already exist') . '!'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'error' => $validator->getMessageBag()
      ], 400);
    }

    Subscriber::query()->create($request->all());


    return Response::json([
      'success' => __('You have successfully subscribed to our newsletter') . '.'
    ], 200);
  }


  public function changeLanguage(Request $request)
  {
    // put the selected language in session
    $langCode = $request['lang_code'];

    $request->session()->put('currentLocaleCode', $langCode);
    return redirect()->back();
  }


  public function getPageHeading($language)
  { 
   
    if (URL::current() == Route::is('blog')) {
      $pageHeading = $language->pageName()->select('blog_page_title')->first();
    } else if (URL::current() == Route::is('aboutus')) {
      $pageHeading = $language->pageName()->select('about_us_page_title')->first();
    } else if (URL::current() == Route::is('blog.post_details')) {
      $pageHeading = $language->pageName()->select('post_details_page_title')->first();
    } else if (URL::current() == Route::is('faq')) {
      $pageHeading = $language->pageName()->select('faq_page_title')->first();
    } else if (URL::current() == Route::is('contact')) {
      $pageHeading = $language->pageName()->select('contact_page_title')->first();
    } else if (URL::current() == Route::is('user.login')) {
      $pageHeading = $language->pageName()->select('login_page_title')->first();
    } else if (URL::current() == Route::is('user.signup')) {
      $pageHeading = $language->pageName()->select('signup_page_title')->first();
    } else if (URL::current() == Route::is('user.forget_password')) {
      $pageHeading = $language->pageName()->select('forget_password_page_title')->first();
    } else if (URL::current() == Route::is('space.index')) {
      $pageHeading = $language->pageName()->select('spaces_page_title')->first();
    } else if (URL::current() == Route::is('space.details')) {
      $pageHeading = $language->pageName()->select('space_details_page_title')->first();
    } else if (URL::current() == Route::is('vendor.signup')) {
      $pageHeading = $language->pageName()->select('seller_signup_page_title')->first();
    } else if (URL::current() == Route::is('vendor.login')) {
      $pageHeading = $language->pageName()->select('seller_login_page_title')->first();
    } else if (URL::current() == Route::is('vendor.forget.password')) {
      $pageHeading = $language->pageName()->select('seller_forget_password_page_title')->first();
    } else if (URL::current() == Route::is('vendor.reset.password')) {
      $pageHeading = $language->pageName()->select('forget_password_page_title')->first();
    } else if (URL::current() == Route::is('vendor.reset.password')) {
      $pageHeading = $language->pageName()->select('forget_password_page_title')->first();
    } else if (URL::current() == Route::is('frontend.sellers')) {
      $pageHeading = $language->pageName()->select('vendor_page_title')->first();
    } elseif (URL::current() == Route::is('shop.products')) {
      $pageHeading = $language->pageName()->select('shop_page_title')->first();
    } elseif (URL::current() == Route::is('shop.cart')) {
      $pageHeading = $language->pageName()->select('cart_page_title')->first();
    } elseif (URL::current() == Route::is('shop.checkout')) {
      $pageHeading = $language->pageName()->select('checkout_page_title')->first();
    } else if (URL::current() == Route::is('space.index')) {
      $pageHeading = $language->pageName()->select('space_page_title')->first();
    }else if (URL::current() == Route::is('pricing')) {
      $pageHeading = $language->pageName()->select('pricing_page_title')->first();
    }
    else if (URL::current() == Route::is('user.dashboard')) {
      $pageHeading = $language->pageName()->select('customer_edit_profile_page_title')->first();
    } else if (URL::current() == Route::is('user.edit_profile')) {
      $pageHeading = $language->pageName()->select('customer_change_password_page_title')->first();
    } else if (URL::current() == Route::is('user.change_password')) {
      $pageHeading = $language->pageName()->select('customer_order_page_title')->first();
    } else if (URL::current() == Route::is('user.order.index')) {
      $pageHeading = $language->pageName()->select('customer_order_details_page_title')->first();
    } else if (URL::current() == Route::is('user.order.details')) {
      $pageHeading = $language->pageName()->select('customer_order_details_page_title')->first();
    } else if (URL::current() == Route::is('user.space_bookings')) {
      $pageHeading = $language->pageName()->select('customer_booking_page_title')->first();
    } else if (URL::current() == Route::is('frontend.user.space-booking-details')) {
      $pageHeading = $language->pageName()->select('customer_booking_details_page_title')->first();
    }
    else {
      $pageHeading = null;
    }

    return $pageHeading;
  }


  public static function getBreadcrumb()
  {
    $breadcrumb = Basic::query()->pluck('breadcrumb')->first();

    return $breadcrumb;
  }
 
  public function countAdView($id)
  {
    try {
      $ad = Advertisement::query()->findOrFail($id);

      $ad->update([
        'views' => $ad->views + 1
      ]);

      return response()->json(['success' => __('Advertisement view counted successfully') . '.']);
    } catch (ModelNotFoundException $e) {
      return response()->json(['error' => __('Sorry, something went wrong') . '!']);
    }
  }


  public function serviceUnavailable()
  {
    $info = Basic::query()->select('maintenance_img', 'maintenance_msg')->first();

    return view('errors.503', compact('info'));
  }

}
