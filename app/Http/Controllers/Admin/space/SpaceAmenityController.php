<?php

namespace App\Http\Controllers\Admin\space;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\SpaceAmenity;
use App\Models\SpaceContent;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;


class SpaceAmenityController extends Controller
{
  public function index(Request $request)
  {
    $name = null;
    if ($request->filled('name')) {
      $name = $request['name'];
    }
    $language = getAdminLanguage();
    $data['language'] = $language;

    // then, get the room amenities of that language from db
    $data['amenities'] = SpaceAmenity::where('language_id', $language->id)
    ->where('name', 'like', '%' . $name . '%')
      ->orderBy('id', 'desc')
      ->paginate(10);

    // also, get all the languages from db
    $data['langs'] = Language::all();
    return view('admin.space-management.amenities.index', $data);
  }

  public function store(Request $request)
  {

    $rules = [
      'language_id' => 'required',
      'icon' => 'required',
      'name' => 'required|string',
      'serial_number' => 'required|numeric',
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }
    $language = Language::where('id', $request->language_id)->first();
    SpaceAmenity::query()->create($request->except('language_id') + [
      'language_id' => $language->id
    ]);
    $request->session()->flash('success', __('New space amenity added successfully') . '!');
    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {

    $rules = [
      'icon' => 'required',
      'name' => 'required|string',
      'serial_number' => 'required|numeric',
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }
    $data = SpaceAmenity::where([
      ['language_id', $request->language],
      ['id', $request->id],
    ])->firstOrFail();
    $data->update($request->all());
    $request->session()->flash('success', __('Space amenities updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function delete(Request $request)
  {
    $id = $request->id;
    $this->deleteAmenity($id);
    return redirect()->back()->with('success', __('Space amenities deleted successfully') . '!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteAmenity($id);
    }

    $request->session()->flash('success', __('Space amenities deleted successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }
  public function deleteAmenity($id)
  {
    $amenity = SpaceAmenity::query()->findOrFail($id);

    if ($amenity) {
      // Fetch all SpaceContent records
      $spaceContents = SpaceContent::all();
      foreach ($spaceContents as $spaceContent) {
        // Decode the amenities JSON array
        $amenities = json_decode($spaceContent->amenities, true);

        // Check if the amenity ID is in the decoded array
        if (is_array($amenities) && in_array($id, $amenities)) {
          // Delete the SpaceContent record
          $spaceContent->delete();
        }
      }
    }
    $amenity->delete();
  }
}
