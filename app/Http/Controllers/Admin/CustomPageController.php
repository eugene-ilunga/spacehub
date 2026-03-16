<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Page\UpdateRequest;
use App\Http\Requests\Page\StoreRequest;
use App\Models\CustomPage\Page;
use App\Models\CustomPage\PageContent;
use App\Models\Language;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;

class CustomPageController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = getAdminLanguage();
    $information['language'] = $language;

    // then, get the custom pages of that language from db
    $information['pages'] = Page::query()->join('page_contents', 'pages.id', '=', 'page_contents.page_id')
      ->where('page_contents.language_id', '=', $language->id)
      ->orderByDesc('pages.id')
      ->paginate(10);

    // also, get all the languages from db
    $information['langs'] = Language::all();

    return view('admin.custom-page.index', $information);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    // get all the languages from db
    $information['languages'] = Language::all();

    return view('admin.custom-page.create', $information);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreRequest $request)
  {
    $page = new Page();

    $page->status = $request->status;
    $page->save();

    $languages = Language::all();

    foreach ($languages as $language) {
      $pageContent = new PageContent();
      $pageContent->language_id = $language->id;
      $pageContent->page_id = $page->id;
      $pageContent->title = $request[$language->code . '_title'];
      $pageContent->slug = createSlug($request[$language->code . '_title']);
      $pageContent->content = Purifier::clean($request[$language->code . '_content'], 'youtube');
      $pageContent->meta_keywords = $request[$language->code . '_meta_keywords'];
      $pageContent->meta_description = $request[$language->code . '_meta_description'];
      $pageContent->save();
    }

    $request->session()->flash('success', __('New page added successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $information['page'] = Page::query()->findOrFail($id);

    // get all the languages from db
    $information['languages'] = Language::all();

    return view('admin.custom-page.edit', $information);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateRequest $request, $id)
  {


    $page = Page::query()->findOrFail($id);

    $page->update([
      'status' => $request->status
    ]);

    $languages = Language::all();

    foreach ($languages as $language) {
      $langCode = $language->code;

      // Check if title and content for this language exist in the request
      if ($request->filled($langCode . '_title') || $request->filled($langCode . '_content')) {
        $pageContent = PageContent::query()
          ->where('page_id', $id)
          ->where('language_id', $language->id)
          ->first();

        $data = [
          'page_id' => $id,
          'language_id' => $language->id,
          'title' => $request[$langCode . '_title'],
          'slug' => createSlug($request[$langCode . '_title']),
          'content' => Purifier::clean($request[$langCode . '_content'], 'youtube'),
          'meta_keywords' => $request[$langCode . '_meta_keywords'] ?? null,
          'meta_description' => $request[$langCode . '_meta_description'] ?? null,
        ];

        if ($pageContent) {
          $pageContent->update($data);
        } else {
          PageContent::create($data);
        }
      }
    }

    $request->session()->flash('success', __('Page updated successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $page = Page::query()->findOrFail($id);

    $page->delete();

    return redirect()->back()->with('success', __('Page deleted successfully') . '!');
  }

  /**
   * Remove the selected or all resources from storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $page = Page::query()->findOrFail($id);

      $page->delete();
    }

    $request->session()->flash('success', __('Pages deleted successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }
}
