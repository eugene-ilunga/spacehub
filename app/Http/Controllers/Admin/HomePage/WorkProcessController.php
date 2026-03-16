<?php

namespace App\Http\Controllers\Admin\HomePage;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\WorkProcessSection;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WorkProcessController extends Controller
{
  public function index(Request $request)
  {
    $themeVersion = Basic::query()->pluck('theme_version')->first();
    $information['themeVersion'] = $themeVersion;

    $language = getAdminLanguage();
    $information['language'] = $language;

    $information['workingProcedures'] = $language->workProcessSection()->orderByDesc('id')->paginate(10);

    $information['langs'] = Language::all();

    return view('admin.home-page.work-process-section.index', $information);
  }

  public function storeWorkProcess(Request $request)
  {
    $themeVersion = Basic::query()->pluck('theme_version')->first();

    $rules = [
      'language_id' => 'required',
      'icon' => 'required',
      'color' => 'required',
      'title' => 'required',
      'description' => 'required',
      'number' => [
        'required',
        'integer',
        Rule::unique('features', 'number')->where(function ($query) use ($request) {
          return $query->where('language_id', $request->language_id);
        }),
      ],
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    WorkProcessSection::query()->create($request->except('language'));

    $request->session()->flash('success', __('New working procedure added successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function updateWorkProcess(Request $request)
  {
    $themeVersion = Basic::query()->pluck('theme_version')->first();

    $rules = [
      'icon' => 'required',
      'color' => 'required',
      'title' => 'required',
      'description' => 'required',
 
      'number' => [
        'required',
        'integer',
        Rule::unique('features', 'number')->where(function ($query) use ($request) {
          return $query->where('language_id', $request->language_id);
        }),
      ],
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $workProcess = WorkProcessSection::query()->findOrFail($request->id);

    $workProcess->update($request->except('language'));

    $request->session()->flash('success', __('Work process updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroyWorkProcess($id)
  {
    $workProcess = WorkProcessSection::query()->findOrFail($id);

    $workProcess->delete();

    return redirect()->back()->with('success', __('Work process deleted successfully') . '!');
  }

  public function bulkDestroyWorkProcess(Request $request)
  {
    $ids = $request['ids'];

    foreach ($ids as $id) {
      $workProcess = WorkProcessSection::query()->findOrFail($id);

      $workProcess->delete();
    }

    $request->session()->flash('success', __('Work Processes deleted successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }
}
