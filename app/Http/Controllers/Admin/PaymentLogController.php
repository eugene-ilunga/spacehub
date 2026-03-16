<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\MegaMailer;
use App\Models\BasicSettings\Basic;
use App\Models\Membership;
use App\Models\Package;
use App\Models\Seller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentLogController extends Controller
{

    public function index(Request $request)
    {

        $paymentStatus = $request->payment_status;
        $search = $request->search;
        $username = $request->username;
        $data['memberships'] = Membership::query()->when($search, function ($query, $search) {
            return $query->where('transaction_id', 'like', '%' . $search . '%');
        })->whereHas('seller', function (Builder $query) use ($username) {
            $query->when($username, function ($query, $username) {
                return $query->where('username', 'like', '%' . $username . '%');
            });
        })
            ->when($paymentStatus, function (Builder $query, $paymentStatus) {

                if ($paymentStatus == 'completed') {
                    return $query->where('status', '=', 1);
                } elseif ($paymentStatus == 'pending') {
                    return $query->where('status', '=', 0);
                } elseif ($paymentStatus == 'rejected') {
                    return $query->where('status', '=', 2);
                }
            })
            ->where('seller_id', '!=', 0)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('admin.payment_log.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function transaction(Request $request)
    {
        $search = $request->search;
        $data['memberships'] = Membership::query()
            ->where('admin_id', Auth::guard('web')->user()->id)
            ->when($search, function ($query, $search) {
                return $query->where('transaction_id', $search);
            })
            ->orderBy('expire_date', 'DESC')
            ->paginate(10);
        return view('admin.transaction.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     */
    public function update(Request $request)
    {

        $bs = Basic::first();
        $membership = Membership::query()->where('id', $request->id)->first();
        $seller = Seller::query()->where('id', $membership->seller_id)->first();
        $package = Package::query()->where('id', $membership->package_id)->first();

        $count_membership = Membership::query()->where('seller_id', $membership->seller_id)->count();
        if ($request->status === "1") {
            $member['first_name'] = $seller->first_name;
            $member['last_name'] = $seller->last_name;
            $member['username'] = $seller->username;
            $member['email'] = $seller->email;
            $data['payment_method'] = $membership->payment_method;

            //comparison date
            $date1 = Carbon::createFromFormat('m/d/Y', \Carbon\Carbon::parse($membership->start_date)->format('m/d/Y'));
            $date2 = Carbon::createFromFormat('m/d/Y', \Carbon\Carbon::now()->format('m/d/Y'));
            $result = $date1->gte($date2);

            if ($result) {
                $data['start_date'] = $membership->start_date;
                $data['expire_date'] = $membership->expire_date;
            } else {
                $data['start_date'] = Carbon::today()->format('d-m-Y');
                if ($package->term === "daily") {
                    $data['expire_date'] = Carbon::today()->addDay()->format('d-m-Y');
                } elseif ($package->term === "weekly") {
                    $data['expire_date'] = Carbon::today()->addWeek()->format('d-m-Y');
                } elseif ($package->term === "monthly") {
                    $data['expire_date'] = Carbon::today()->addMonth()->format('d-m-Y');
                } elseif ($package->term === "lifetime") {
                    $data['expire_date'] = Carbon::maxValue()->format('d-m-Y');
                } else {
                    $data['expire_date'] = Carbon::today()->addYear()->format('d-m-Y');
                }
                $membership->update(['start_date' =>  Carbon::parse($data['start_date'])]);
                $membership->update(['expire_date' =>  Carbon::parse($data['expire_date'])]);
            }

            // if previous membership package is lifetime, then exipre that membership
            $previousMembership = Membership::query()
                ->where([
                    ['seller_id', $seller->id],
                    ['start_date', '<=', Carbon::now()->toDateString()],
                    ['expire_date', '>=', Carbon::now()->toDateString()]
                ])
                ->where('status', 1)
                ->orderBy('created_at', 'DESC')
                ->first();

            if (!is_null($previousMembership)) {
                $previousPackage = Package::query()
                    ->select('term')
                    ->where('id', $previousMembership->package_id)
                    ->first();
                if ($previousPackage->term === 'lifetime' || $previousMembership->is_trial == 1) {
                    $yesterday = Carbon::yesterday()->format('d-m-Y');
                    $previousMembership->expire_date = Carbon::parse($yesterday);
                    $previousMembership->save();
                }
            }

            // Update seller status to 1 (active) only for new memberships
            if ($count_membership <= 1) {
                $seller->update(['status' => 1]);
            }

            // Get vendor info
            $vendorInfo = $this->getVendorDetails($membership->seller_id);

            // process invoice data
            $membershipInvoiceData = [
                'name'      => $vendorInfo->seller_name,
                'username'  => $vendorInfo->username,
                'email'     => $vendorInfo->email,
                'phone'     => $vendorInfo->phone,
                'order_id'  => $membership->transaction_id,
                'base_currency_text_position'  => $bs->base_currency_text_position,
                'base_currency_text'  => $bs->base_currency_text,
                'base_currency_symbol'  => $bs->base_currency_symbol,
                'base_currency_symbol_position'  => $bs->base_currency_symbol_position,
                'amount'  => $package->price,
                'payment_method'  => $membership->payment_method,
                'package_title'  => $package->title,
                'start_date'  => $data["start_date"] ?? $membership->start_date,
                'expire_date'  => $data["expire_date"] ?? $membership->expire_date,
                'website_title'  => $bs->website_title,
                'logo'  => $bs->logo,
            ];
            $file_name = $this->makeInvoice($membershipInvoiceData);
            


            $paymentFor = getPaymentType($membership->seller_id, $membership->package_id);

            $currencyFormat = function ($amount) use ($bs) {
                return ($bs->base_currency_text_position == 'left' ? $bs->base_currency_text . ' ' : '')
                    . $amount
                    . ($bs->base_currency_text_position == 'right' ? ' ' . $bs->base_currency_text : '');
            };

            $status = $request->status;

            //process mail data
            $mailData = [
                'toMail' => $vendorInfo->email,
                'toName' => $vendorInfo->fname,
                'username' => $vendorInfo->username,
                'package_title' => $package->title,
                'package_price' => $currencyFormat($package->price),
                'total' => $currencyFormat($membership->price),
                'activation_date' => $data["start_date"] ?? $membership->start_date,
                'expire_date' => $data["expire_date"] ?? $membership->expire_date,
                'membership_invoice' => $file_name,
                'website_title' => $bs->website_title,
                'templateType' => $status == 2
                    ? 'payment_rejected_for_registration_offline_gateway'
                    : ($paymentFor == 'membership'
                        ? 'registration_with_premium_package'
                        : 'membership_extend'),

                'type' => $paymentFor == 'membership'
                    ? 'registrationWithPremiumPackage'
                    : 'membershipExtend'
            ];

            (new MegaMailer())->mailFromAdmin($mailData);

            $membership->update(['status' => $status]);
            $membership->update(['invoice' => $file_name]);

            $transaction = [
                'order_number' => $membership->transaction_id,
                'transaction_type' => 5,
                'user_id' => null,
                'seller_id' => $membership->seller_id,
                'payment_status' => 'completed',
                'payment_method' => $membership->payment_method,
                'sub_total' => $membership->price,
                'grand_total' => $membership->price,
                'tax' => null,
                'gateway_type' => 'offline',
                'currency_symbol' => $membership->currency_symbol,
                'currency_symbol_position' => $bs->base_currency_symbol_position,
            ];
            storeTransaction($transaction);

            $earnings = [
                'life_time_earning' => $membership->price,
                'total_profit' => $membership->price,
            ];
            storeEarnings($earnings);
        } 
        elseif ($request->status == 2) {
            if ($count_membership > 1) {

                $mailTemplate = 'payment_rejected_for_membership_extension_offline_gateway';
                $mailType = 'paymentRejectedForMembershipExtensionOfflineGateway';
            } else {

                $mailTemplate = 'payment_rejected_for_registration_offline_gateway';
                $mailType = 'paymentRejectedForRegistrationOfflineGateway';
            }

            $mailer = new MegaMailer();
            $data = [
                'toMail' => $seller->email,
                'toName' => $seller->fname,
                'username' => $seller->username,
                'package_title' => $package->title,
                'package_price' => ($bs->base_currency_symbol_position == 'left' ? $bs->base_currency_text . ' ' : '') . $package->price . ($bs->base_currency_symbol_position == 'right' ? ' ' . $bs->base_currency_text : ''),
                'website_title' => $bs->website_title,
                'templateType' => $mailTemplate,
                'type' => $mailType
            ];
            $mailer->mailFromAdmin($data);
        }


        $membership->update(['status' => $request->status]);

        session()->flash('success', __('Membership status changed successfully') . "!");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function getVendorDetails($sellerId)
    {
        return Seller::select('sellers.*', 'seller_infos.name as seller_name')
            ->leftJoin('seller_infos', function ($join) use ($sellerId) {
                $join->on('sellers.id', '=', 'seller_infos.seller_id')
                    ->where('sellers.id', $sellerId);
            })
            ->where('sellers.id', '=', $sellerId)
            ->first();
    }
}
