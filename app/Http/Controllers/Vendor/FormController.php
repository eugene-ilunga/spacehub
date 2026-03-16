<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SellerPermissionHelper;
use App\Models\Form;
use App\Models\ClientService\ServiceContent;
use App\Models\Language;
use App\Models\SpaceContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    public function index(Request $request)
    {
    
        $language = getVendorLanguage();
        $information['language'] = $language;

        if(request()->filled('name')) {
            $name = $request->name;
        } else {
            $name = null;
        }
        $information['forms'] = $language->form()->where('seller_id', Auth::guard('seller')->user()->id)
        ->when($name, function ($query) use ($name){
            return $query->where('name', 'LIKE', '%'. $name . '%');
        })
        ->orderByDesc('id')->paginate(10);

        $information['langs'] = Language::all();

        return view('vendors.form.index', $information);
    }

    public function store(Request $request)
    {
        $vendor_id = Auth::guard('seller')->user()->id;
        $language = getVendorLanguage();

        if ($vendor_id != 0) {
            $hasMembership = SellerPermissionHelper::currentPackagePermission($vendor_id);

            if ($hasMembership == null) {
                // Vendor does not have an active membership
                session()->flash('warning', __('It appears that you currently do not have a membership') . '. ' . __('Please consider purchasing a plan to enjoy our services') . '.');
                return response()->json([
                    'status' => 'no_membership',
                    'redirect_url' => route('vendor.plan.extend.index', ['language' => $language->code]),
                ], 200);
            }
        }

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
        $in = $request->all();
        $in['seller_id'] = Auth::guard('seller')->user()->id;

        Form::query()->create($in);

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
        $form = Form::where([['id', $request['id']], ['seller_id', Auth::guard('seller')->user()->id]])->firstOrFail();

        $form->update($request->all());

        $request->session()->flash('success', __('Form updated successfully') . '!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id, Request $request)
    {
        $form = Form::query()->where([['id', '=', $id], ['seller_id', Auth::guard('seller')->user()->id]])->first();

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
            $request->session()->flash('error', __('Sorry') . ', ' . __('this form cannot be deleted right now') . '. ' . __('This form is attached with the services').'. ' . __('Either you have to delete those services or change the form of those services') . '.');

            return redirect()->back();
        }
    }
}
