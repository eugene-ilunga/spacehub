<?php

namespace App\Http\Controllers\Admin\Footer;

use App\Http\Controllers\Controller;
use App\Models\Footer\QuickLink;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class QuickLinkController extends Controller
{
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = getAdminLanguage();
    $information['language'] = $language;

    // then, get the quick-links of that language from db
    $information['quickLinks'] = $language->footerQuickLink()->orderByDesc('id')->paginate(10);

    // also, get all the languages from db
    $information['langs'] = Language::all();

    return view('admin.footer.quick-link.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'title' => 'required',
      'url' => 'required',
      'serial_number' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    QuickLink::query()->create($request->all());

    $request->session()->flash('success', __('New quick link added successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rules = [
      'title' => 'required',
      'url' => 'required',
      'serial_number' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $quickLink = QuickLink::query()->findOrFail($request->id);

    $quickLink->update($request->all());

    $request->session()->flash('success', __('Quick link updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $quickLink = QuickLink::query()->findOrFail($id);

    $quickLink->delete();

    return redirect()->back()->with('success', __('Quick link deleted successfully') . '!');
  }
}
