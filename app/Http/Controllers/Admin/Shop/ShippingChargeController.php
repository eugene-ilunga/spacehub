<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Shop\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ShippingChargeController extends Controller
{
  public function index(Request $request)
  {
    $title = null;
    if ($request->filled('title')) {
      $title = $request['title'];
    }
    // first, get the language info from db
    $language = getAdminLanguage();
    $information['language'] = $language;


    // then, get the shipping charge of that language from db
    $query = $language->shippingCharge()->orderByDesc('id');

    if ($title) {
      $query->where('title', 'like', '%' . $title . '%');
    }
    $information['charges'] = $query->paginate(10);

    // get all the languages from db
    $information['langs'] = Language::all();

    // also, get the currency information from db
    $information['currencyInfo'] = $this->getCurrencyInfo();

    return view('admin.shop.shipping-charge.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'title' => 'required',
      'short_text' => 'required',
      'shipping_charge' => 'required',
      'serial_number' => 'required'
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    ShippingCharge::query()->create($request->all());

    $request->session()->flash('success', __('New shipping charge added successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rules = [
      'title' => 'required',
      'short_text' => 'required',
      'shipping_charge' => 'required',
      'serial_number' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $shippingCharge = ShippingCharge::query()->find($request->id);

    $shippingCharge->update($request->all());

    $request->session()->flash('success', __('Shipping charge updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $shippingCharge = ShippingCharge::query()->find($id);

    $shippingCharge->delete();

    return redirect()->back()->with('success', __('Shipping charge deleted successfully') . '!');
  }
}
