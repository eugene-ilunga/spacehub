<?php

namespace App\Http\Controllers\Admin\space;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\SpaceCategory;
use App\Models\SpaceContent;
use App\Models\SpaceSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminSpaceSubCategoryController extends Controller
{
  public function index(Request $request)
  {
    $language = getAdminLanguage();
    $data['language'] = $language;
    // Get filter values from request
    $categoryId = $request->input('category');
    $subcategoryId = $request->input('subcategory');
    $status = $request->input('status');

    // Base query for subcategories
    $subcategoriesQuery = $language->spaceSubcategory();

    // Apply category filter if selected
    if ($categoryId) {
      $subcategoriesQuery->whereHas('category', function ($q) use ($categoryId) {
        $q->where('id', $categoryId);
      });
    }

    // Apply subcategory filter if selected
    if ($subcategoryId) {
      $subcategoriesQuery->where('id', $subcategoryId);
    }

    // Apply status filter if selected
    if ($status) {
      if ($status == 'active') {
        $subcategoriesQuery->where('status', 1);
      } elseif ($status == 'inactive') {
        $subcategoriesQuery->where('status', 0);
      }
    }

    // Get paginated results
    $subcategories = $subcategoriesQuery->orderByDesc('id')->paginate(10);

    $subcategories->map(function ($subcategory) use ($language) {
      $category = $subcategory->category()->where('language_id', '=', $language->id)->first();
      $subcategory['categoryName'] = $category->name ?? null;
    });

    $data['subcategories'] = $subcategories;
    $data['langs'] = Language::all();

    $data['categories'] = $language->spaceCategory()->orderBy('serial_number', 'asc')->get();
    $data['filterCategories'] = $language->spaceCategory()->orderBy('name')->get();


    // Get subcategories for dropdown (filtered by selected category if applicable)
    $filterSubcategoriesQuery = $language->spaceSubcategory();
    if ($categoryId) {
      $filterSubcategoriesQuery->whereHas('category', function ($q) use ($categoryId) {
        $q->where('id', $categoryId);
      });
    }

    $data['filterSubcategories'] = $filterSubcategoriesQuery->orderBy('name')->get();


    return view('admin.space-management.sub-category.index', $data);
  }

  public function getCategories(Request $request)
  {

    $categories = SpaceCategory::where([
      ['language_id', $request->language_id],
      ['status', 1],
    ])->get();
    return response()->json($categories);
  }


  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'space_category_id' => 'required',
      'name' => [
        'required',
        Rule::unique('space_sub_categories')->where(function ($query) use ($request) {
          return $query->where('language_id', $request->input('language_id'));
        })
      ],
      'status' => 'required|numeric',
      'serial_number' => 'required|numeric'
    ];


    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    SpaceSubCategory::query()->create($request->except('slug') + [
        'slug' => createSlug($request['name'])
      ]);

    $request->session()->flash('success', __('New subcategory added successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }
  public function update(Request $request)
  {
    $subcategory = SpaceSubcategory::query()->find($request->id);
    $rules = [
      'space_category_id' => 'required',
      'name' => [
        'required',
        Rule::unique('space_sub_categories')->where(function ($query) use ($subcategory) {
          return $query->where('language_id', $subcategory->language_id);
        })->ignore($request->id)
      ],
      'status' => 'required|numeric',
      'serial_number' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $subcategory->update($request->except('slug') + [
        'slug' => createSlug($request['name'])
      ]);

    $request->session()->flash('success', __('Subcategory updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }
  public function destroy($id)
  {
    $this->deleteSubcategory($id);

    return redirect()->back()->with('success', __('Subcategory deleted successfully') . '!');
  }
  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteSubcategory($id);
    }

    $request->session()->flash('success', __('Subcategories deleted successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }



  // subcategory deletion code
  public function deleteSubcategory($id)
  {
    $subcategory = SpaceSubCategory::query()->findOrFail($id);
    if($subcategory)
    {
          // Find all space content related to this subcategory
          $spaceContents = SpaceContent::where('sub_category_id', $subcategory->id)->get();
          foreach ($spaceContents as $spaceContent)
          {
            $spaceContent->delete();
          }
      }
    $subcategory->delete();
  }

}
