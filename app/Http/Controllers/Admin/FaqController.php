<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = getAdminLanguage();
    $information['language'] = $language;

    // then, get the faqs of that language from db
    $information['faqs'] = $language->faq()->orderByDesc('id')->paginate(10);

    // also, get all the languages from db
    $information['langs'] = Language::all();

    return view('admin.faq.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'question' => 'required',
      'answer' => 'required',
      'serial_number' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    FAQ::query()->create($request->all());

    $request->session()->flash('success', __('New faq added successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rules = [
      'question' => 'required',
      'answer' => 'required',
      'serial_number' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $faq = FAQ::query()->find($request->id);

    $faq->update($request->all());

    $request->session()->flash('success', __('FAQ updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $faq = FAQ::query()->find($id);

    $faq->delete();

    return redirect()->back()->with('success', __('FAQ deleted successfully') . '!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $faq = FAQ::query()->find($id);

      $faq->delete();
    }

    $request->session()->flash('success', __('FAQs deleted successfully'). '!');

    return Response::json(['status' => 'success'], 200);
  }
}
