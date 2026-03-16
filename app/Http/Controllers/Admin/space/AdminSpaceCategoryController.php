<?php

namespace App\Http\Controllers\Admin\space;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\SpaceCategory;
use App\Models\SpaceContent;
use App\Rules\ImageDimensions;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminSpaceCategoryController extends Controller
{
  public function index(Request $request)
  {
    
    $data['themeVersion'] = Basic::select('theme_version')->first();
    $language = getAdminLanguage();
    $data['language'] = $language;

    // Get all categories for the dropdown filter
    $allCategories = $language->spaceCategory()->orderBy('name')->get();
    $data['filterCategories'] = $allCategories;

    // Start building the query with status filtering
    $categoriesQuery = $language->spaceCategory();

    // Apply category filter if selected
    if ($request->filled('category')) {
      $categoriesQuery->where('id', $request->category);
    }

    // Apply status filter if selected
    if ($request->filled('status')) {
      if ($request->status == 'active') {
        $categoriesQuery->where('status', 1);
      } elseif ($request->status == 'inactive') {
        $categoriesQuery->where('status', 0);
      }
    }

    // Paginate the results
    $data['categories'] = $categoriesQuery->orderByDesc('id')->paginate(10);

    $data['langs'] = Language::all();
    return view('admin.space-management.space-category.index', $data);
  }

  public function store(Request $request)
  {
  

    $themeVersion = Basic::select('theme_version')->first();
    $rules = [
      'language_id' => 'required',
      'icon' => 'required',
      'icon_image' => [
        'required',
        new ImageMimeTypeRule(),
        new ImageDimensions(65, 75, 65, 75)
      ],

      'name' => [
        'required',
        Rule::unique('space_categories')->where(function ($query) use ($request) {
          return $query->where('language_id', $request->input('language_id'));
        })
      ],
      'status' => 'required|numeric',
      'category_description' => 'nullable',
      'serial_number' => 'required|numeric'
    ];
    if($themeVersion->theme_version == 2)
    {
      $rules['bg_image'] = [
        'required',
        new ImageMimeTypeRule(),
        new ImageDimensions(740, 760, 850, 860)
      ];
    }
   
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    // store image in storage
    if($themeVersion->theme_version == 2)
    {
      $iconImgName = UploadFile::store('./assets/img/space-categories/', $request->file('icon_image'));
      $bgImgName = UploadFile::store('./assets/img/space-categories/background', $request->file('bg_image'));


      SpaceCategory::query()->create($request->except('icon_image','bg_image', 'slug') + [
          'icon_image' => $iconImgName,
          'bg_image' => $bgImgName,
          'slug' => createSlug($request['name'])
        ]);
    }
    else{
      $iconImgName = UploadFile::store('./assets/img/space-categories/', $request->file('icon_image'));

      SpaceCategory::query()->create($request->except('icon_image', 'slug') + [
          'icon_image' => $iconImgName,
          'slug' => createSlug($request['name'])
        ]);
    }



    $request->session()->flash('success', __('New category added successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function updateFeaturedStatus(Request $request, $id)
  {
    $category = SpaceCategory::find($id);

    if ($request->has('is_featured')) {
      $category->is_featured = $request->input('is_featured');
      $category->save();

      if ($category->is_featured) {
        $request->session()->flash('success', __('Category featured successfully') . '!');
      } else {
        $request->session()->flash('success', __('Category unfeatured successfully') . '!');
      }
    }

    return redirect()->back();
  }
  public function update(Request $request)
  {
    
    $category = SpaceCategory::query()->findOrFail($request->id);
    $themeVersion = Basic::select('theme_version')->first();

    $rules = [
      'category_description' => 'nullable',
      'name' => [
        'required',
        Rule::unique('space_categories')->where(function ($query) use ($category) {
          return $query->where('language_id', $category->language_id);
        })->ignore($request->id)
      ],
      'status' => 'required|numeric',
      'serial_number' => 'required|numeric',
      'icon' => 'required',
    ];

    // Apply image validation only if a new image is uploaded
    if ($request->hasFile('icon_image')) {
      $rules['icon_image'] = [
        new ImageMimeTypeRule(), 
        new ImageDimensions(65, 75, 65, 75) 
      ];
    }

    if($themeVersion->theme_version == 2)
    {
      $rules['bg_image'] = 'required';
      // If bg_image is uploaded, validate type & dimensions
      if ($request->hasFile('bg_image')) {
        $rules['bg_image'] = [
          'required',
          new ImageMimeTypeRule(),
          new ImageDimensions(740, 760, 850, 860) 
        ];
      }
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }
    if($themeVersion->theme_version == 2){
      if ($request->hasFile('icon_image')) {
        $newImage = $request->file('icon_image');
        $oldImage = $category->icon_image;

        if (!empty($oldImage) && file_exists(public_path('assets/img/space-categories/' . $oldImage))) {
          unlink(public_path('assets/img/space-categories/' . $oldImage));
        }
        $iconImgName = UploadFile::update('./assets/img/space-categories/', $newImage, $oldImage);
      }
      if ($request->hasFile('bg_image')) {
        $newBgImage = $request->file('bg_image');
        $oldBgImage = $category->bg_image;
        if (!empty($oldBgImage) && file_exists(public_path('assets/img/space-categories/background' . $oldBgImage))) {
          unlink(public_path('assets/img/space-categories/background' . $oldBgImage));
        }
        $bgImgName = UploadFile::update('./assets/img/space-categories/background', $newBgImage, $oldBgImage);
      }

      $category->update($request->except('icon_image','bg_image','slug') + [
          'icon_image' => isset($iconImgName) ? $iconImgName : $category->icon_image,
          'bg_image' => isset($bgImgName) ? $bgImgName : $category->bg_image,
          'slug' => createSlug($request['name'])
        ]);
    }
    else{
      if ($request->hasFile('icon_image')) {
        $newImage = $request->file('icon_image');
        $oldImage = $category->icon_image;
        $iconImgName = UploadFile::update('./assets/img/space-categories/', $newImage, $oldImage);
      }
      $category->update($request->except('icon_image', 'slug') + [
          'icon_image' => isset($iconImgName) ? $iconImgName : $category->icon_image,
          'slug' => createSlug($request['name'])
        ]);
    }

    $request->session()->flash('success', __('Category updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $this->deleteCategory($id);

    return redirect()->back()->with('success', __('Category deleted successfully') . '!');
  }


  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteCategory($id);
    }

    $request->session()->flash('success', __('Categories deleted successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  // category deletion code
  public function deleteCategory($id)
  {
    $category = SpaceCategory::query()->findOrFail($id);

    if($category)
    {
      // delete all the subcategories of this category
      $subcategories = $category->subcategory()->get();

      if (count($subcategories) > 0) {
        foreach ($subcategories as $subCategory) {
          // Find all space content related to this subcategory
          SpaceContent::where('sub_category_id', $subCategory->id)->delete();
          // Delete the subcategory
          $subCategory->delete();
        }
      }
      // Delete all space content directly related to the category
      SpaceContent::where('space_category_id', $id)->delete();

      $iconImagePath = !empty($category->icon_image) ? public_path('assets/img/space-categories/' . $category->icon_image) : null;
      $bgImagePath = !empty($category->bg_image) ? public_path('assets/img/space-categories/background' . $category->bg_image) : null;
      if (file_exists($iconImagePath) && !empty($iconImagePath)) {
        @unlink($iconImagePath);
      }
      if (file_exists($bgImagePath) && !empty($bgImagePath)) {
        @unlink($bgImagePath);
      }

      $category->delete();
    }
  }

}
