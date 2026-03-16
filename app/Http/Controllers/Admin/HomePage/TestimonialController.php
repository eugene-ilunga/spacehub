<?php

namespace App\Http\Controllers\Admin\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Testimonial\StoreRequest;
use App\Http\Requests\Testimonial\UpdateRequest;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\Testimonial;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
  public function index(Request $request)
  {
    $information['bgImg'] = Basic::query()->pluck('testimonial_bg_img')->first();

    $language = getAdminLanguage();
    $information['language'] = $language;

    $information['testimonials'] = $language->testimonial()->orderByDesc('id')->paginate(10);

    $information['langs'] = Language::all();

    return view('admin.home-page.testimonial-section.index', $information);
  }

  public function updateBgImg(Request $request)
  {
    $bgImg = Basic::query()->pluck('testimonial_bg_img')->first();

    $rules = [];

    if (empty($bgImg)) {
      $rules['testimonial_bg_img'] = 'required';
    }
    if ($request->hasFile('testimonial_bg_img')) {
      $rules['testimonial_bg_img'] = new ImageMimeTypeRule();
    }


    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('testimonial_bg_img')) {
      $newImage = $request->file('testimonial_bg_img');
      $oldImage = $bgImg;

      $imgName = UploadFile::update('./assets/img/', $newImage, $oldImage);

      Basic::query()->updateOrCreate(
        ['uniqid' => 12345],
        ['testimonial_bg_img' => $imgName]
      );

      $request->session()->flash('success', __('Image updated successfully') . '!');
    }

    return redirect()->back();
  }


  public function storeTestimonial(StoreRequest $request)
  {
    // store image in storage
    $imgName = UploadFile::store('./assets/img/clients/', $request->file('image'));

    Testimonial::query()->create($request->except('language', 'image') + [
      'image' => $imgName
    ]);

    $request->session()->flash('success', __('New testimonial added successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }

  public function updateTestimonial(UpdateRequest $request)
  {
    $testimonial = Testimonial::query()->find($request->id);

    if ($request->hasFile('image')) {
      $newImage = $request->file('image');
      $oldImage = $testimonial->image;
      $imgName = UploadFile::update('./assets/img/clients/', $newImage, $oldImage);
    }

    $testimonial->update($request->except('language', 'image') + [
      'image' => $request->hasFile('image') ? $imgName : $testimonial->image
    ]);

    $request->session()->flash('success', __('Testimonial updated successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroyTestimonial($id)
  {
    $testimonial = Testimonial::query()->find($id);

    @unlink(public_path('assets/img/clients/' . $testimonial->image));

    $testimonial->delete();

    return redirect()->back()->with('success', __('Testimonial deleted successfully') . '!');
  }

  public function bulkDestroyTestimonial(Request $request)
  {
    $ids = $request['ids'];

    foreach ($ids as $id) {
      $testimonial = Testimonial::query()->find($id);

      @unlink(public_path('assets/img/clients/' . $testimonial->image));

      $testimonial->delete();
    }

    $request->session()->flash('success', __('Testimonials deleted successfully') . '!');

    return response()->json(['status' => 'success'], 200);
  }
}
