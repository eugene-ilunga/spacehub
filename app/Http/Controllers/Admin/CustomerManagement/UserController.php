<?php

namespace App\Http\Controllers\Admin\CustomerManagement;

use App\Http\Controllers\Controller;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\UploadFile;
use App\Models\BasicSettings\Basic;
use App\Models\Follower;
use App\Models\Language;
use App\Models\SupportTicket;
use App\Models\User;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
  public function settings(Request $request)
  {
    $language = getAdminLanguage();
    $user_settings = Basic::select('guest_checkout_status')->first();
    return view('admin.end-user.user.setting', compact('language', 'user_settings'));
  }

  public function settingUpdated(Request $request)
  {

    $rules = [
      'guest_checkout_status'                     => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return response()->json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $user_settings                         = Basic::first();
    $user_settings->guest_checkout_status                    = $request->guest_checkout_status;
    $user_settings->save();

    Session::flash('success', __('User settings update successfully') . '!');
    return Response::json(['status' => 'success'], 200);
  }

  public function index(Request $request)
  {
    $searchKey = null;

    if ($request->filled('info')) {
      $searchKey = $request['info'];
    }

    $users = User::query()->when($searchKey, function ($query, $searchKey) {
      return $query->where('username', 'like', '%' . $searchKey . '%')
        ->orWhere('email_address', 'like', '%' . $searchKey . '%');
    })
      ->orderByDesc('id')
      ->paginate(10);

    return view('admin.end-user.user.index', compact('users'));
  }

  public function registerUser(Request $request)
  {
    $request->validate([
      'username' => 'required|unique:users|max:255',
      'email_address' => 'required|email:rfc,dns|unique:users|max:255',
      'password' => 'required|confirmed',
      'password_confirmation' => 'required',
    ]);
    $websiteTitle = Basic::query()->pluck('website_title')->first();

    $user = new User();
    $user->username = $request->username;
    $user->email_address = $request->email_address;
    $user->password = Hash::make($request->password);


    $user->email_verified_at = date('Y-m-d H:i:s');
    $user->status = 1;
    $user->verification_token = null;
    $user->save();

    /**
     * prepare a verification mail and, send it to user to verify his/her email address,
     * get the mail template information from db
     */

    $bs = Basic::select('website_title')->first();
    $mailer = new MegaMailer();
    $data = [
      'toMail' => $request->email_address,
      'toName' => $request->username,
      'username' => $request->username,
      'password' => $request->password,
      'user_type' => 'customer',
      'website_title' => $bs->website_title,
      'templateType' => 'add_user_by_admin'
    ];
    $mailer->mailFromAdmin($data);

    Session::flash('success', __('Customer added successfully') .'!');
    return Response::json(['status' => 'success'], 200);
  }

  public function updateEmailStatus(Request $request, $id)
  {
    $user = User::query()->find($id);

    if ($request['email_status'] == 'verified') {
      $user->update([
        'email_verified_at' => date('Y-m-d H:i:s')
      ]);
    } else {
      $user->update([
        'email_verified_at' => NULL
      ]);
    }

    $request->session()->flash('success', __('Email status updated successfully') . '!');

    return redirect()->back();
  }

  public function updateAccountStatus(Request $request, $id)
  {
    $user = User::query()->find($id);

    if ($request['account_status'] == 1) {
      $user->update([
        'status' => 1
      ]);
    } else {
      $user->update([
        'status' => 0
      ]);
    }

    $request->session()->flash('success', __('Account status updated successfully') . '!');

    return redirect()->back();
  }

  public function edit($id)
  {
    $user = User::query()->findOrFail($id);
    $information['user'] = $user;
    return view('admin.end-user.user.edit', $information);
  }

  public function update(Request $request, $id)
  {
    $rules = [
      'first_name' => 'required',
      'last_name' => 'required',
      'username' => [
        'required',
        Rule::unique('users', 'username')->ignore($id)
      ],
      'email_address' => [
        'required',
        Rule::unique('users', 'email_address')->ignore($id)
      ],
      'phone_number' => 'required',
      'city' => 'required',
      'country' => 'required',
      'address' => 'required',
    ];

    $user = User::findOrFail($id);

    // Handle image upload
    if ($request->hasFile('image')) {
      $image = $request->file('image');

      // 1. Validate MIME type using your custom rule
      $mimeRule = new ImageMimeTypeRule();
      if (!$mimeRule->passes('image', $image)) {
        return response()->json(['errors' => ['image' => [$mimeRule->message()]]], 400);
      }

      // 2. Manually validate dimensions
      try {
        list($width, $height) = getimagesize($image->getPathname());

        if ($width !== 80 || $height !== 80) {
          return response()->json([
            'errors' => [
              'image' => ['The image must be exactly 80x80 pixels.']
            ]
          ], 400);
        }
      } catch (\Exception $e) {
        return response()->json([
          'errors' => [
            'image' => ['Could not read image dimensions.']
          ]
        ], 400);
      }

      // Process the upload
      $uploadPath = public_path('assets/img/users/');

      if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0755, true);
      }

      $imageName = UploadFile::update($uploadPath, $image, $user->image);
      $user->image = $imageName;
    }

    // Validate other fields
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['errors' => $validator->getMessageBag()], 400);
    }

    // Update user data
    $user->update($request->except('image'));

    Session::flash('success', __('Updated customer information successfully') . '.');
    return response()->json(['status' => 'success'], 200);
  }

  public function show($id)
  {
    $user = User::query()->findOrFail($id);
    $information['userInfo'] = $user;

    return view('admin.end-user.user.details', $information);
  }

  public function changePassword($id)
  {
    $userInfo = User::query()->findOrFail($id);

    return view('admin.end-user.user.change-password', compact('userInfo'));
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
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $user = User::query()->find($id);

    $user->update([
      'password' => Hash::make($request->new_password)
    ]);

    $request->session()->flash('success', __('Password updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $this->deleteUser($id);

    return redirect()->back()->with('success', __('Customer deleted successfully') . '!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteUser($id);
    }

    $request->session()->flash('success', __('Customers deleted successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function deleteUser($id)
  {
    $user = User::query()->find($id);

    // delete all the service orders of this user
    $spaceBookings = $user->spaceBooking()->get();

    if (count($spaceBookings) > 0) {
      foreach ($spaceBookings as $order) {

        // delete booking receipt 
        @unlink(public_path('assets/img/attachments/space/' . $order->receipt));

        // delete booking invoice
        @unlink(public_path('assets/file/invoices/space/' . $order->invoice));

        // delete booking order
        $order->delete();
      }
    }

    // delete all the service reviews of this user
    $spaceReviews = $user->spaceReview()->get();
    if (count($spaceReviews) > 0) {
      foreach ($spaceReviews as $review) {
        $review->delete();
      }
    }

    // delete all the support tickets of this user
    $tickets = SupportTicket::where([['user_id', $user->id], ['user_type', 'user']])->get();
 
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

    // delete all the wishlisted services of this user
    $wishlistedSpaces = $user->spaceWishlisted()->get();

    if (count($wishlistedSpaces) > 0) {
      foreach ($wishlistedSpaces as $space) {
        $space->delete();
      }
    }

    // delete user image
    @unlink(public_path('assets/img/users/' . $user->image));

    // delete user info from db
    $user->delete();
  }

  public function secretLogin(Request $request, $id)
  {
    $user = User::where('id', $id)->first();
    if ($user) {
      Auth::guard('web')->login($user, true);

      return redirect()->route('user.dashboard')
        ->withSuccess(__('You have Successfully logged in as'). ' ' . $user->username);
    }

    return redirect()->route('user.login')->withSuccess('Oppes'. '! ' . 'You have entered invalid credentials');
  }
}
