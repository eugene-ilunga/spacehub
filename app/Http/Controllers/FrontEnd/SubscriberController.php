<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
  public function store(Request $request)
  {

    $rules = [
      'email_id' => 'required|email:rfc,dns|unique:subscribers'
    ];

    $messages = [
      'email_id.required' => __('Please enter your email address'). '.',
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
      'success' => __('You have successfully subscribed to our newsletter'). '.'
    ], 200);
  }
}
