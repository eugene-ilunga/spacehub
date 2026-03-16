<?php

namespace App\Http\Controllers\Admin\BasicSettings;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SocialMediaController extends Controller
{
  public function index()
  {
    $information['medias'] = SocialMedia::query()->orderByDesc('id')->paginate(10);

    return view('admin.basic-settings.social-media.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'icon' => 'required',
      'url' => 'required|url',
      'serial_number' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    SocialMedia::query()->create($request->all());

    $request->session()->flash('success', __('New social media added successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rules = [
      'url' => 'required|url',
      'serial_number' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $socialMedia = SocialMedia::query()->find($request->id);

    $socialMedia->update($request->all());

    $request->session()->flash('success', __('Social media updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $socialMedia = SocialMedia::query()->find($id);

    $socialMedia->delete();

    return redirect()->back()->with('success', __('Social media deleted successfully') . '!');
  }
}
