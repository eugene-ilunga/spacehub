<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\UploadFile;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\Blog\Post;
use App\Models\Membership;
use App\Models\Seller;
use App\Models\Space;
use App\Models\SpaceBooking;
use App\Models\Subscriber;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\User;
use App\Rules\ImageMimeTypeRule;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
  public function login()
  {

    return view('admin.account.login');
  }

  public function authentication(Request $request)
  {
    $rules = [
      'username' => 'required',
      'password' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    // get the username and password which has provided by the admin
    $credentials = $request->only('username', 'password');
    // get language form session
    $language = getAdminLanguage();

    if (Auth::guard('admin')->attempt($credentials)) {
      $authAdmin = Auth::guard('admin')->user();

      // check whether the admin's account is active or not
      if ($authAdmin->status == 0) {
        $request->session()->flash('alert', __('Sorry, your account has been deactivated') . '!');

        // logout auth admin as condition not satisfied
        Auth::guard('admin')->logout();

        return redirect()->back();
      } else {

        return redirect()->route('admin.dashboard', ['language' => $language->code]);
      }
    } else {
      return redirect()->back()->with('alert', __('Oops, username or password does not match') . '!');
    }
  }

  public function forgetPassword()
  {
    return view('admin.account.forget-password');
  }

  public function forgetPasswordMail(Request $request)
  {
    // validation start
    $rules = [
      'email' => [
        'required',
        'email:rfc,dns',
        new MatchEmailRule('admin')
      ]
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors())->withInput();
    }
    // validation end

    // create a new password and store it in db
    $newPassword = uniqid();

    $admin = Admin::query()->where('email', '=', $request->email)->first();

    $admin->update([
      'password' => Hash::make($newPassword)
    ]);

    // prepare a mail to send newly created password to admin
    $mailData['subject'] = __('Reset Password');

    $mailData['body'] = __('Hi') . ' ' . $admin->first_name . ',<br/><br/>' . ' ' . __('Your password has been reset. Your new password is') . ': ' . $newPassword . '<br/>' . __('Now, you can login with your new password') . '. ' . __('You can change your password later') . '.' . '<br/><br/>' . __('Thank you') . '.';


    $mailData['recipient'] = $admin->email;

    $mailData['sessionMessage'] = __('A mail has been sent to your email address') . '.';

    BasicMailer::sendMail($mailData);

    return redirect()->back();
  }

  public function redirectToDashboard()
  {
    $information['authAdmin'] = Auth::guard('admin')->user();
    $information['spaces'] = Space::query()->count();
    $information['spaceBookings'] = SpaceBooking::query()->count();
    $information['posts'] = Post::query()->count();
    $information['users'] = User::query()->count();
    $information['sellers'] = Seller::query()->where('id', '!=', 0)->count();
    $information['memberships'] = Membership::query()->where('seller_id', '!=', 0)->count();
    $information['subscribers'] = Subscriber::query()->count();
    $information['support_tickets'] = SupportTicket::query()->count();
    $information['total_transaction'] = Transaction::query()->count();

    $monthWiseSpaceBookings = DB::table('space_bookings')
      ->select(DB::raw('month(created_at) as month'), DB::raw('count(id) as total_space_bookings'))
      ->where('payment_status', '=', 'completed')
      ->groupBy('month')
      ->whereYear('created_at', '=', date('Y'))
      ->get();
      
    $monthWiseSubscriptions = DB::table('memberships')
      ->select(DB::raw('month(created_at) as month'), DB::raw('count(id) as total_subscription'))
      ->where([['status', 1], ['seller_id', '!=', 0]])
      ->groupBy('month')
      ->whereYear('created_at', '=', date('Y'))
      ->get();

    $months = [];
    $totalSpaceBookings = [];
    $totalSubscription = [];

    for ($i = 1; $i <= 12; $i++) {
      // get all 12 months name
      $monthNum = $i;
      $dateObj = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('M');
      array_push($months, $monthName);

      // get all 12 months's space bookings
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
      // get all 12 months's space bookings
      $subscriptionFound = false;

      foreach ($monthWiseSubscriptions as $subscription) {
        if ($subscription->month == $i) {
          $subscriptionFound = true;
          array_push($totalSubscription, $subscription->total_subscription);
          break;
        }
      }

      if ($subscriptionFound == false) {
        array_push($totalSubscription, 0);
      }
    }

    $information['months'] = $months;
    $information['totalSpaceBookings'] = $totalSpaceBookings;
    $information['subscriptionArr'] = $totalSubscription;

    return view('admin.dashboard.index', $information);
  }

  public function changeTheme(Request $request)
  {

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      ['admin_theme_version' => $request->admin_theme_version]
    );
    return redirect()->back();
  }

  public function monthly_earning(Request $request)
  {
    if ($request->filled('year')) {
      $date = $request->input('year');
    } else {
      $date = date('Y');
    }
    $monthWiseTotalIncomes = DB::table('transactions')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where('payment_status', 'completed')
      ->whereIn('transcation_type', [1, 7])
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();


    $months = [];
    $incomes = [];
    for ($i = 1; $i <= 12; $i++) {
      // get all 12 months name
      $monthNum = $i;
      $dateObj = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('F');
      array_push($months, $monthName);

      // get all 12 months's income of equipment booking
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
    }
    $information['months'] = $months;
    $information['incomes'] = $incomes;

    return view('admin.dashboard.lifetime-earning', $information);
  }

  //monthly  income
  public function monthly_profit(Request $request)
  {
    if ($request->filled('year')) {
      $date = $request->input('year');
    } else {
      $date = date('Y');
    }

    // this tax amount from seller space 
    $monthWiseTotalTaxes = DB::table('transactions')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(tax) as total'))
      ->where([['seller_id', '!=', null], ['payment_status', 'completed']])
      ->whereIn('transcation_type', [1])
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();

    //get grand total from admin space 
    $monthWiseTotalSpaceBookingIncomes = DB::table('transactions')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where([['seller_id', '=', null], ['payment_status', 'completed']])
      ->whereIn('transcation_type', [1])
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();

    //get grand total from seller buy plan
    $monthWiseTotalSubscriptionIncomes = DB::table('transactions')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where('payment_status', 'completed')
      ->whereIn('transcation_type', [5])
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();

    //get grand total from seller to make feature for space
    $monthWiseTotalSpaceFeatureIncomes = DB::table('transactions')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where('payment_status', 'completed')
      ->whereIn('transcation_type', [6])
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();

    //get grand total from admin services
    $monthWiseTotalProductPurchaseIncomes = DB::table('transactions')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where([['seller_id', null], ['payment_status', 'completed']])
      ->whereIn('transcation_type', [7])
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();

    $months = [];
    $taxes = [];
    $spaceBookingIncomes = [];
    $subscriptionIncomes = [];
    $spaceFeatureIncomes = [];
    $productPurchageIncomes = [];

    for ($i = 1; $i <= 12; $i++) {
      // get all 12 months name
      $monthNum = $i;
      $dateObj = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('M');
      array_push($months, $monthName);

      // get all 12 months's taxes of  booking
      $taxFound = false;
      foreach ($monthWiseTotalTaxes as $taxInfo) {
        if ($taxInfo->month == $i) {
          $taxFound = true;
          array_push($taxes, $taxInfo->total);
          break;
        }
      }
      if ($taxFound == false) {
        array_push($taxes, 0);
      }

      // get all 12 months's space booking only for admin space
      $bookingFound = false;
      foreach ($monthWiseTotalSpaceBookingIncomes as $space) {
        if ($space->month == $i) {
          $bookingFound = true;
          array_push($spaceBookingIncomes, $space->total);
          break;
        }
      }
      if ($bookingFound == false) {
        array_push($spaceBookingIncomes, 0);
      }

      // get all 12 months's income of booking
      $subFound = false;
      foreach ($monthWiseTotalSubscriptionIncomes as $subInfo) {
        if ($subInfo->month == $i) {
          $subFound = true;
          array_push($subscriptionIncomes, $subInfo->total);
          break;
        }
      }
      if ($subFound == false) {
        array_push($subscriptionIncomes, 0);
      }

      // get all 12 months's income of space feature
      $spaceFeatureFound = false;
      foreach ($monthWiseTotalSpaceFeatureIncomes as $spaceFeature) {
        if ($spaceFeature->month == $i) {
          $spaceFeatureFound = true;
          array_push($spaceFeatureIncomes, $spaceFeature->total);
          break;
        }
      }
      if ($spaceFeatureFound == false) {
        array_push($spaceFeatureIncomes, 0);
      }

      // get all 12 months's income of product purchage
      $incomeFound = false;
      foreach ($monthWiseTotalProductPurchaseIncomes as $incomeInfo) {
        if ($incomeInfo->month == $i) {
          $incomeFound = true;
          array_push($productPurchageIncomes, $incomeInfo->total);
          break;
        }
      }
      if ($incomeFound == false) {
        array_push($productPurchageIncomes, 0);
      }
    }
    $information['months'] = $months;
    $information['taxes'] = $taxes;
    $information['spaceBookingIncomes'] = $spaceBookingIncomes;
    $information['subscriptionIncomes'] = $subscriptionIncomes;
    $information['spaceFeatureIncomes'] = $spaceFeatureIncomes;
    $information['productPurchageIncomes'] = $productPurchageIncomes;

    // Total Profit per month
    $totalProfits = [];
    for ($i = 0; $i < 12; $i++) {
      $totalProfits[$i] =
        $taxes[$i] +
        $spaceBookingIncomes[$i] +
        $subscriptionIncomes[$i] +
        $spaceFeatureIncomes[$i] +
        $productPurchageIncomes[$i];
    }
    $information['totalProfits'] = $totalProfits;


    return view('admin.dashboard.total-profit', $information);
  }

  public function editProfile()
  {
    $adminInfo = Auth::guard('admin')->user();
    $space_settings = Basic::select('admin_profile')->first();

    return view('admin.account.edit-profile', compact('adminInfo', 'space_settings'));
  }

  public function updateProfile(Request $request)
  {
    $admin = Admin::where('id', Auth::guard('admin')->user()->id)->firstOrFail();

    $rules = [];

    if (is_null($admin->image)) {
      $rules['image'] = 'required';
    }
    if ($request->hasFile('image')) {
      $rules['image'] = new ImageMimeTypeRule();
    }

    $rules['username'] = [
      'required',
      Rule::unique('admins')->ignore($admin->id)
    ];

    $rules['email'] = [
      'required',
      'email:rfc,dns',
      Rule::unique('admins')->ignore($admin->id)
    ];

    $rules['first_name'] = 'required';

    $rules['last_name'] = 'required';
    $rules['phone'] = 'required';
    $rules['address'] = 'required';

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('image')) {
      $newImg = $request->file('image');
      $oldImg = $admin->image;
      $imageName = UploadFile::update('./assets/img/admins/', $newImg, $oldImg);
    }

    $admin->update([
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'image' => $request->hasFile('image') ? $imageName : $admin->image,
      'username' => $request->username,
      'phone' => $request->phone,
      'address' => $request->address,
      'email' => $request->email
    ]);

    $space_settings = Basic::first();

    if ($space_settings) {
      $space_settings->admin_profile = $request->input('admin_profile');
      $space_settings->save();
    } else {
      // Optionally, create a new Basic record if none exists
      Basic::create([
        'admin_profile' => $request->input('admin_profile')
      ]);
    }

    $request->session()->flash('success', __('Profile updated successfully') . '!');

    return redirect()->back();
  }

  public function changePassword()
  {
    return view('admin.account.change-password');
  }

  public function updatePassword(Request $request)
  {
    $rules = [
      'current_password' => [
        'required',
        new MatchOldPasswordRule('admin')
      ],
      'new_password' => 'required|confirmed',
      'new_password_confirmation' => 'required'
    ];

    $messages = [
      'new_password.confirmed' => __('Password confirmation does not match') . '.',
      'new_password_confirmation.required' => __('The confirm new password field is required') . '.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $admin = Admin::where('id', Auth::guard('admin')->user()->id)->firstOrFail();

    $admin->update([
      'password' => Hash::make($request->new_password)
    ]);

    $request->session()->flash('success', __('Password updated successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }

  public function logout(Request $request)
  {
    Auth::guard('admin')->logout();

    session()->forget('admin_lang');
    return redirect()->route('admin.account.login');
  }

  //transaction
  public function transaction(Request $request)
  {

    $transaction_id = null;
    if ($request->filled('transaction_id')) {
      $transaction_id = $request->transaction_id;
    }

    $info['transactions'] = Transaction::when($transaction_id, function ($query) use ($transaction_id) {
      return $query->where('transcation_id', 'like', '%' . $transaction_id . '%');
    })->orderByDesc('id')->paginate(10);

    return view('admin.dashboard.transaction', $info);
  }
}
