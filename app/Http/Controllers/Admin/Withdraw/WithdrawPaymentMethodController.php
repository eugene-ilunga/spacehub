<?php

namespace App\Http\Controllers\Admin\Withdraw;

use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawPaymentMethodRequest;
use App\Models\WithdrawPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WithdrawPaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $information = [];
        $collection =  WithdrawPaymentMethod::paginate(10);
        $information['collection'] = $collection;
        return view('admin.withdraw.index', $information);
    }

    public function store(WithdrawPaymentMethodRequest $request)
    {       
        WithdrawPaymentMethod::create($request->all());
        Session::flash('success', __('New Withdraw Payment Method Added successfully') . '!');
        return response()->json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                Rule::unique('withdraw_payment_methods', 'name')->ignore($request->id, 'id')
            ],
            'min_limit' => 'required|numeric',
            'max_limit' => 'required|numeric',
            'status' => 'required'
        ]);

        // Retrieve existing WithdrawPaymentMethod
        $withdrawPaymentMethod = WithdrawPaymentMethod::where('id', $request->id)->first();
        $fixed_charge = $request->input('fixed_charge', $withdrawPaymentMethod->fixed_charge);
        $percentage_charge = $request->input('percentage_charge', $withdrawPaymentMethod->percentage_charge);
       

        // Custom validation: Check if min_limit is greater than the sum of fixed_charge and percentage_charge
        if ($request->min_limit <= ($fixed_charge + $percentage_charge)) {
           
            // Add a custom error message
            $validator->after(function ($validator) use ($fixed_charge, $percentage_charge) {
                $validator->errors()->add('min_limit', __('The minimum limit must be greater than') . ': ' . __('result') . '. ', ['result' => $fixed_charge + $percentage_charge]);
            });
          
        }
        if ($validator->fails()) {
            return response()->json([
                    'errors' => $validator->getMessageBag()
                ], 400);
        }
       
        
        WithdrawPaymentMethod::where('id', $request->id)->first()->update($request->all());
        Session::flash('success', __('Update Withdraw Payment Method successfully') . '!');

        return response()->json(['status' => 'success'], 200);
    }
    public function destroy($id)
    {
        WithdrawPaymentMethod::where('id', $id)->first()->delete();

        return redirect()->back()->with('success', __('Withdraw Payment Method deleted successfully') . '!');
    }
}
