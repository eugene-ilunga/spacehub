<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SellerPermissionHelper;
use App\Models\BasicSettings\Basic;
use App\Models\Membership;
use App\Models\Package;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;


class BuyPlanController extends Controller
{
  public function index()
  {

    $membership = Membership::first();
    $abs = Basic::first();
    Config::set('app.timezone', $abs->timezone);

    $currentLang = getVendorLanguage();
    $data['bex'] = $currentLang->basic_extended;
    $data['packages'] = Package::where('status', '1')->where('id', '<>', 999999)->get();

    $nextPackageCount = Membership::query()->where([
      ['seller_id', Auth::guard('seller')->user()->id],
      ['expire_date', '>=', Carbon::now()->toDateString()]
    ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count();

    //current package
    $data['current_membership'] = Membership::query()->where([
      ['seller_id', Auth::guard('seller')->user()->id],
      ['start_date', '<=', Carbon::now()->toDateString()],
      ['expire_date', '>=', Carbon::now()->toDateString()]
    ])->where('status', 1)->whereYear('start_date', '<>', '9999')->first();

    if ($data['current_membership'] != null) {

      // Define the message parts
      $messagePart1 = __('Please note that if you choose to buy a different package');
      $messagePart2 = __('you will not be able to access the features of your previous package');


      // Determine the punctuation based on the locale
      $comma = ($currentLang->direction === 1) ? '،' : ',';
      $period = ($currentLang->direction === 1) ? '.' : '.';

      $message = $messagePart1 . $comma . ' ' . $messagePart2 . $period;
      $data['message'] = $message;

      $countCurrMem = Membership::query()->where([
        ['seller_id', Auth::guard('seller')->user()->id],
        ['start_date', '<=', Carbon::now()->toDateString()],
        ['expire_date', '>=', Carbon::now()->toDateString()]
      ])->where('status', 1)->whereYear('start_date', '<>', '9999')->count();

      if ($countCurrMem > 1) {
        $data['next_membership'] = Membership::query()->where([
          ['seller_id', Auth::guard('seller')->user()->id],
          ['start_date', '<=', Carbon::now()->toDateString()],
          ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
      } else {
        $data['next_membership'] = Membership::query()->where([
          ['seller_id', Auth::guard('seller')->user()->id],
          ['start_date', '>', $data['current_membership']->expire_date]
        ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
      }
      $data['next_package'] = $data['next_membership'] ? Package::query()->where('id', $data['next_membership']->package_id)->first() : null;
    } else {
      $data['next_package'] = null;
    }
    $current_package = $data['current_membership'] ? Package::query()->where('id', $data['current_membership']->package_id)->first() : null;
    $data['current_package'] = $current_package;
    $data['package_count'] = $nextPackageCount;

    $data['previousPackageId'] = isset($current_package) && (!empty($current_package)) ? $current_package->id :  'new_vendor';

    $packageFeature = Basic::query()->select('package_features')->first();
    $data['allPfeatures'] = json_decode($packageFeature->package_features, true);
    return view('vendors.buy_plan.index', $data);
  }

  public function checkout($package_id)
  {
    $paymentFor = null;

    $packageCount = Membership::query()->where([
      ['seller_id', Auth::guard('seller')->user()->id],
      ['expire_date', '>=', Carbon::now()->toDateString()]
    ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count();


    $hasPendingMemb = SellerPermissionHelper::hasPendingMembership(Auth::guard('seller')->user()->id);

    if ($hasPendingMemb) {
      Session::flash('warning', __('You already have a Pending Membership Request') . '.');
      return back();
    }
    if ($packageCount >= 2) {
      Session::flash('warning', __('You have another package to activate after the current package expires') . '. ' . __('You cannot purchase / extend any package, until the next package is activated'));
      return back();
    }

    $currentLang = getVendorLanguage();

    $be = $currentLang->basic_extended;
    $online = OnlineGateway::query()->where('status', 1)->get();
    $offline = OfflineGateway::where('status', 1)->orderBy('serial_number', 'asc')->get();

    $offline = $offline->map(function ($item) {
      $item->payment_type = 'offline'; // Add payment_type attribute
      return $item;
    });
    $data['offline'] = $offline;
    $data['payment_methods'] = $online->concat($offline);

    $data['package'] = Package::query()->findOrFail($package_id);
    $data['membership'] = Membership::query()->where([
      ['seller_id', Auth::guard('seller')->user()->id],
      ['expire_date', '>=', \Carbon\Carbon::now()->format('Y-m-d')]
    ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')
      ->latest()
      ->first();
    $data['previousPackage'] = null;
    if (!is_null($data['membership'])) {
      $data['previousPackage'] = Package::query()
        ->where('id', $data['membership']->package_id)
        ->first();
    }
    $data['bex'] = $be;

    $stripe = OnlineGateway::where('keyword', 'stripe')->first();
    $stripe_info = json_decode($stripe->information, true);
    $data['stripe_key'] = $stripe_info['key'];

    //current package
    $current_membership = Membership::query()->where([
      ['seller_id', Auth::guard('seller')->user()->id],
      ['start_date', '<=', Carbon::now()->toDateString()],
      ['expire_date', '>=', Carbon::now()->toDateString()]
    ])->where('status', 1)->whereYear('start_date', '<>', '9999')->first();

    $current_package = $current_membership ? Package::query()->where('id', $current_membership->package_id)->first() : null;

    if ($packageCount < 2 && !$hasPendingMemb) {
      if (isset($current_package->id) && $current_package->id === $data['package']->id) {
        $payment_for = 'extend';
      } else {
        $payment_for = 'membership';
      }
    }

    $data['payment_for'] = $payment_for;

    // Define error messages and add them to the data array
    $data = array_merge($data, [
      'stripeError' => __('Your card number is incomplete'),
      'anetCardError' => __('Please provide valid credit card number'),
      'anetYearError' => __('Please provide valid expiration year'),
      'anetMonthError' => __('Please provide valid expiration month'),
      'anetExpirationDateError' => __('Expiration date must be in the future'),
      'anetCvvInvalidError' => __('Please provide valid CVV gfgf'),
      'paymentGatewayError' => __('Payment gateway is required'),
      'firstNameError' => __('First name is required'),
      'phoneNumberError' => __('Phone number is required'),
      'emailAddressError' => __('Email address is required'),
    ]);

    return view('vendors.buy_plan.checkout', $data);
  }
}
