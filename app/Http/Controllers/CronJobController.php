<?php

namespace App\Http\Controllers;

use App\Http\Controllers\FrontEnd\OrderProcessController;
use App\Http\Controllers\FrontEnd\Shop\PurchaseProcessController;
use App\Http\Helpers\SellerPermissionHelper;
use App\Jobs\SubscriptionExpiredMail;
use App\Jobs\SubscriptionReminderMail;
use App\Models\BasicSettings\Basic;
use App\Models\Membership;
use App\Models\Package;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use Carbon\Carbon;
use App\Http\Helpers\MegaMailer;
use App\Models\Shop\ProductOrder;
use App\Models\SpaceBooking;
use App\Models\SpaceContent;
use App\Models\SpaceFeature;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CronJobController extends Controller
{
    public function processBooking()
    {

        try {
            \Artisan::call('queue:work --stop-when-empty');
        } catch (\Exception $e) {
  
        }
    }

    public function expired()
    {
        try {
            $bs = Basic::first();

            $expired_members = Membership::whereDate('expire_date', Carbon::now()->subDays(1))->get();
            foreach ($expired_members as $key => $expired_member) {
                if (!empty($expired_member->seller)) {
                    $seller = $expired_member->seller;
                    $current_package = SellerPermissionHelper::userPackage($seller->id);
                    if (is_null($current_package)) {
                        SubscriptionExpiredMail::dispatch($seller, $bs);
                    }
                }
            }

            $remind_members = Membership::whereDate('expire_date', Carbon::now()->addDays($bs->expiration_reminder))->get();
            foreach ($remind_members as $key => $remind_member) {
                if (!empty($remind_member->seller)) {
                    $seller = $remind_member->seller;

                    $nextPacakgeCount = Membership::where([
                        ['seller_id', $seller->id],
                        ['start_date', '>', Carbon::now()->toDateString()]
                    ])->where('status', '<>', 2)->count();

                    if ($nextPacakgeCount == 0) {
                        SubscriptionReminderMail::dispatch($seller, $bs, $remind_member->expire_date);
                    }
                }

                \Artisan::call("queue:work --stop-when-empty");
            }
        } catch (\Exception $e) {
        }
    }

    public function checkPayment()
    {
        //check iyzico pending  membership
        $this->checkPendingMemberships();

        //check iyzico pending space feature
        $this->checkPendingSpaceFeatures();

        // Check pending space bookings
        $this->checkPendingSpaceBookings();

        // Check pending for product purchase
        $this->checkPendingProductOrder();
    }

    protected function checkPendingProductOrder()
    {
        $pendingProductOrders = ProductOrder::where([
            ['payment_status', 'pending'],
            ['payment_method', 'Iyzico']
        ])->get();

        foreach ($pendingProductOrders as $order) {
            if (!$order->conversation_id) continue;

            try {
                $result = $this->IyzicoPaymentStatus($order->conversation_id);

                if ($result === 'success') {
                    $this->completeProductOrder($order);
                }

            } catch (\Exception $e) {
                Log::error("Iyzico check failed for booking {$order->id}: " . $e->getMessage());
            }
        }
    }

    protected function checkPendingMemberships()
    {
        $iyzico_pending_memberships = Membership::where([['status', 0], ['payment_method', 'Iyzico']])->get();

        foreach ($iyzico_pending_memberships as $iyzico_pending_membership) {
            if (!is_null($iyzico_pending_membership->conversation_id)) {
                $result = $this->IyzicoPaymentStatus($iyzico_pending_membership->conversation_id);
                if ($result == 'success') {
                    $this->updateIyzicoPendingMemership($iyzico_pending_membership->id, 1);
                } else {
                    $this->updateIyzicoPendingMemership($iyzico_pending_membership->id, 2);
                }
            }
        }
    }

    protected function checkPendingSpaceFeatures()
    {
        $iyzico_pending_orders = SpaceFeature::where([['payment_status', 'pending'], ['payment_method', 'Iyzico']])->get();

        foreach ($iyzico_pending_orders as $iyzico_pending_order) {
            if (!is_null($iyzico_pending_order->conversation_id)) {
                $result = $this->IyzicoPaymentStatus($iyzico_pending_order->conversation_id);
                if ($result == 'success') {
                    $this->updateIyzicoPendingSpaceFeaturedOrder($iyzico_pending_order->id, $iyzico_pending_order->seller_id, 'completed');
                }
            }
        }
    }

    protected function checkPendingSpaceBookings()
    {
        $pendingBookings = SpaceBooking::where([
            ['payment_status', 'pending'],
            ['payment_method', 'Iyzico']
        ])->get();

        foreach ($pendingBookings as $booking) {
            if (!$booking->conversation_id) continue;

            try {
                $result = $this->IyzicoPaymentStatus($booking->conversation_id);

                if ($result === 'success') {
                    $this->completeSpaceBooking($booking);
                }

            } catch (\Exception $e) {
                Log::error("Iyzico check failed for booking {$booking->id}: " . $e->getMessage());
            }
        }
    }

    // get iyzico payment status from iyzico server

    private function IyzicoPaymentStatus($conversation_id)
    {
        
        $paymentMethod = OnlineGateway::where('keyword', 'iyzico')->first();
        $paydata = json_decode($paymentMethod->information, true);

        $options = new \Iyzipay\Options();
        $options->setApiKey($paydata['api_key']);
        $options->setSecretKey($paydata['secret_key']);
        if ($paydata['iyzico_mode'] == 1) {
            $options->setBaseUrl("https://sandbox-api.iyzipay.com");
        } else {
            $options->setBaseUrl("https://api.iyzipay.com");
        }

        $request = new \Iyzipay\Request\ReportingPaymentDetailRequest();
        $request->setPaymentConversationId($conversation_id);

        $paymentResponse = \Iyzipay\Model\ReportingPaymentDetail::create($request, $options);
        $result = (array) $paymentResponse;

        foreach ($result as $key => $data) {
            $data = json_decode($data, true);
            if ($data['status'] == 'success' && !empty($data['payments'])) {
                if (is_array($data['payments'])) {
                    if ($data['payments'][0]['paymentStatus'] == 1) {
                        return 'success';
                    } else {
                        return 'not found';
                    }
                } else {
                    return 'not found';
                }
            } else {
                return 'not found';
            }
        }
        return 'not found';
    }

    //update pending memberships if payment is successfull
    private function updateIyzicoPendingMemership($id, $status)
    {
        $bs = Basic::first();
        $membership = Membership::query()->findOrFail($id);
        $seller = Seller::query()->findOrFail($membership->seller_id);

        // Get vendor info
        $vendorInfo = $this->getVendorDetails($membership->seller_id);
        $package = Package::query()->findOrFail($membership->package_id);

        $count_membership = Membership::query()->where('seller_id', $membership->seller_id)->count();

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
                ['seller_id', $vendorInfo->id],
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
            'payment_method'  => 'Iyzico',
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

        $transaction = [
            'order_number' => $membership->id,
            'transaction_type' => 5,
            'user_id' => null,
            'seller_id' => $membership->seller_id,
            'payment_status' => 'completed',
            'payment_method' => $membership->payment_method,
            'sub_total' => $membership->price,
            'grand_total' => $membership->price,
            'tax' => null,
            'gateway_type' => 'online',
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

    private function updateIyzicoPendingSpaceFeaturedOrder($order_id, $seller_id, $status)
    {
        try {
            $bs = Basic::first();
            $order = SpaceFeature::where('id', $order_id)->first();
            if ($order) {
                $user  = Seller::where('id', $seller_id)->first();
                if ($user) {
                    $order->payment_status = $status;
                    $order->save();

                    // Store transaction
                    $transaction_data = [
                        'order_number' => $order->booking_number,
                        'transaction_type' => 6,
                        'user_id' => null,
                        'seller_id' => $order->seller_id,
                        'payment_status' => 'completed',
                        'payment_method' => $order->payment_method,
                        'grand_total' => $order->total,
                        'sub_total' => $order->total,
                        'tax' => null,
                        'gateway_type' => 'online',
                        'currency_symbol' => $order->currency_symbol,
                        'currency_symbol_position' => $order->currency_symbol_position
                    ];
                    
                    storeTransaction($transaction_data);

                    // Store earnings
                    storeEarnings([
                        'life_time_earning' => $order->total,
                        'total_profit' => $order->total
                    ]);

                    $defaultLang = getVendorLanguage();

                    // Get space content details
                    $spaceContent = SpaceContent::query()->select('title', 'slug')->where([
                        ['space_id', $order->space_id],
                        ['language_id', $defaultLang->id]
                    ])->first();

                    //set url and space title
                    $url = $spaceContent ? route('space.details', [
                        'slug' => $spaceContent->slug,
                        'id' => $order->space_id
                    ]) : null;

                    $spaceName = $spaceContent ? $spaceContent->title : null;

                    // Get vendor info
                    $vendorInfo = $this->getVendorDetails($order->seller_id);

                    $vendorName = $vendorInfo->seller_name;

                    $featureInvoiceData = [
                        'name'      => $vendorInfo->seller_name,
                        'username'  => $vendorInfo->username,
                        'email'     => $vendorInfo->email,
                        'phone'     => $vendorInfo->phone,
                        'order_id'  => $order->booking_number,
                        'base_currency_text_position'  => $bs->base_currency_text_position,
                        'base_currency_text'  => $bs->base_currency_text,
                        'base_currency_symbol'  => $bs->base_currency_symbol,
                        'base_currency_symbol_position'  => $bs->base_currency_symbol_position,
                        'amount'  => $order->total,
                        'payment_method'  => $order->payment_method,
                        'space_title'  => $spaceName,
                        'start_date'  => $order->start_date,
                        'expire_date'  => $order->end_date,
                        'website_title'  => $bs->website_title,
                        'logo'  => $bs->logo,
                        'day'  => $order->days,
                        'purpose'  => 'feature',
                    ];

                    // Generate and update invoice
                    $invoice = $this->makeInvoice($featureInvoiceData);
                    $order->update(['invoice' => $invoice]);

                    // Send payment confirmation email
                    SpaceFeature::sendPaymentStatusEmail(
                        $order,
                        $url,
                        $spaceName,
                        $vendorName,
                        $bs->website_title,
                        'featured_request_payment_approved',
                        $order->invoice
                    );
                }
            }
        } catch (\Exception $th) {
        }
    }

    protected function completeSpaceBooking($booking)
    {
        DB::transaction(function () use ($booking) {
            // Update booking status
            $booking->update(['payment_status' => 'completed']);

            // Process vendor payment
            $vendorData = [
                'sub_total' => $booking->sub_total ?? 0,
                'seller_id' => $booking->seller_id ?? null
            ];
            storeAmountToSeller($vendorData);

            // Process admin earnings
            $adminData = [
                'life_time_earning' => $booking->grand_total ?? 0,
                'total_profit' => $booking->seller_id ? $booking->tax : $booking->grand_total
            ];
            storeEarnings($adminData);

            // Generate invoice if not exists
            if (!$booking->invoice) {
                $orderProcess = new OrderProcessController();
                $invoice = $orderProcess->generateInvoice($booking);
                $booking->update(['invoice' => $invoice]);
            }

            // Send notifications
            $orderProcess->prepareMail($booking);
            $orderProcess->prepareMailForVendor($booking);
        });
    }
    protected function completeProductOrder($order)
    {
        DB::transaction(function () use ($order) {
            // Update booking status
            $order->update(['payment_status' => 'completed']);

            // Process admin earnings
            $adminData = [
                'life_time_earning' => $order->grand_total ?? 0,
                'total_profit' => $order->seller_id ? $order->tax : $order->grand_total
            ];
           

            $order['transaction_type'] = 7;
            storeTransaction($order);

            storeEarnings($adminData);

            $orderProcess = new PurchaseProcessController();
            // Send notifications
            $orderProcess->prepareMail($order);
        });
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
