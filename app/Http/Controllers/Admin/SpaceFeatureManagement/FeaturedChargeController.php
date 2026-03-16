<?php

namespace App\Http\Controllers\Admin\SpaceFeatureManagement;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\FeatureCharge;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class FeaturedChargeController extends Controller
{
  public function index()
  {
    $language = Language::query()->where('is_default', '=', 1)->firstOrFail();
    $data['language'] = $language;
    $data['basic'] = Basic::select('base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position')->first();

    $data['categories'] = FeatureCharge::query()->orderByDesc('id')->paginate(10);
    $data['langs'] = Language::all();
    return view('admin.featured-management.charge.index', $data);
  }

  public function store(Request $request)
  {
   
    $rules = [
      'number_of_day' =>  'required|integer',
      'charge_price' => 'required|numeric',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    // store image in storage

    $featureCharge = new FeatureCharge();
    $featureCharge->day = $request->number_of_day;
    $featureCharge->price = $request->charge_price;

    $featureCharge->save();

    $request->session()->flash('success', __('New feature charge added successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }
  public function update(Request $request)
  {
    $featureCharge = FeatureCharge::findOrFail($request->id);

    $rules = [
      'number_of_day' => 'required|integer',
      'charge_price' => 'required|numeric',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $featureCharge->update([
      'day' => $request->number_of_day,
      'price' => $request->charge_price,
    ]);

    $request->session()->flash('success', __('Feature charge updated successfully') .  '!');

    return Response::json(['status' => 'success'], 200);
  }
  public function destroy(Request $request)
  {
    $id = $request->id;
    $this->deleteFeatureCharge($id);

    return redirect()->back()->with('success', __('Feature charge deleted successfully') . '!');
  }
  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteFeatureCharge($id);
    }

    $request->session()->flash('success', __('Feature charges deleted successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function deleteFeatureCharge($id)
  {
    $featureCharge = FeatureCharge::query()->find($id);
    $featureCharge->delete();
  }
}
