<?php

namespace App\Http\Controllers\Admin\AboutUs;

use App\Http\Controllers\Controller;
use App\Models\AboutContent;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AboutContentController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;
    $information['aboutContents'] = $language->aboutContent()->orderByDesc('id')->paginate(10);

    $information['langs'] = Language::all();

    return view('admin.about-us.about-content.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'sub_title' => 'required',
      'sub_text' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }
    AboutContent::query()->create($request->except('slug') + [
        'slug' => createSlug($request['sub_title'])
      ]);

    $request->session()->flash('success', __('New About Content added successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $aboutContent = AboutContent::query()->find($request->id);

    $rules = [
      'sub_title' => 'required',
      'sub_text' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $aboutContent->fill($request->only('language_id', 'sub_title', 'sub_text'));
    $aboutContent->save();

    $request->session()->flash('success', __('About Content updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }


  public function destroy($id)
  {
    $aboutContent = AboutContent::find($id);

    if ($aboutContent) {
      $aboutContent->delete();
      return redirect()->back()->with('success', __('About Content deleted successfully') . '!');
    } else {
      return redirect()->back()->with('error', __('About Content not found') . '!');
    }
  }




  public function bulkDestroy(Request $request)
  {

    $ids = $request->ids;

    foreach ($ids as $id) {
      AboutContent::query()->find($id)->delete();
    }

    $request->session()->flash('success', __('About contents deleted successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }
}
