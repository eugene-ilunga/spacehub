<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Language;
use App\Models\Seller;
use App\Models\SpaceContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
  public function index(Request $request)
  {
    $language = getAdminLanguage();
    $information['language'] = $language;

    $information['sellers'] = Seller::select('username', 'id')->where('id', '!=', 0)->get();
    $seller = $name = null;

    if ($request->filled('seller')) {
      $seller = $request->seller;
    }
    if ($request->filled('name')) {
      $name = $request->name;
    }

    $information['forms'] = $language->form()
      ->when($seller, function ($query) use ($seller) {
        if ($seller == 'admin') {
          $seller_id = null;
        } else {
          $seller_id = $seller;
        }
        return $query->where('seller_id', $seller_id);
      })
      ->when($name, function ($query) use ($name){
        return $query->where('name', 'like', '%' . $name . '%');
      })
      ->orderByDesc('id')->paginate(10);


    $information['langs'] = Language::all();

    return view('admin.space-management.form.index', $information);
  }

  public function store(Request $request)
  {

    $rules = [
      'language_id' => 'required',
      'name' => 'required',
      'status' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    Form::query()->create($request->all());

    $request->session()->flash('success', __('Form added successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rule = [
      'name' => 'required',
      'status' => 'required'
    ];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $form = Form::query()->find($request['id']);

    $form->update($request->all());

    $request->session()->flash('success', __('Form updated successfully') . '!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id, Request $request)
  {
 
    $form = Form::query()->find($id);
  
    $serviceContent = SpaceContent::query()
    ->where('get_quote_form_id', '=', $form->id)
    ->orWhere('tour_request_form_id', '=', $form->id)
    ->first();

    if (empty($serviceContent)) {
      $inputFields = $form->input()->get();

      if (count($inputFields) > 0) {
        foreach ($inputFields as $inputField) {
          $inputField->delete();
        }
      }

      $form->delete();

      $request->session()->flash('success', __('Form deleted successfully') . '!');

      return redirect()->back();
    } else {
      $request->session()->flash('error', __('Sorry') . ', '. __('this form cannot be deleted right now'). '. '. __('This form is attached with the spaces'). '. ' . __('Either you have to delete those spaces or change the form of those spaces')) . '.';

      return redirect()->back();
    }
  }
}
