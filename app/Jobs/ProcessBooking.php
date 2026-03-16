<?php

namespace App\Jobs;

use App\Http\Controllers\FrontEnd\OrderProcessController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SpaceBooking;
use App\Traits\HandlesInvoiceGeneration;
use App\Traits\HandlesMailPreparation;

class ProcessBooking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, HandlesInvoiceGeneration, HandlesMailPreparation;
    protected $bookingId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($bookingId)
    {
        $this->bookingId = $bookingId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $bookingInfo = SpaceBooking::find($this->bookingId);
        if(!$bookingInfo){
            return;
        }

        $orderProcess = new OrderProcessController();
        $invoice = $orderProcess->generateInvoice($bookingInfo);

        $bookingInfo->update(['invoice' => $invoice]);

        $vendorData['sub_total'] = $bookingInfo->sub_total ?? 0;
        $vendorData['seller_id'] = $bookingInfo->seller_id;
        storeAmountToSeller($vendorData);

        $adminData['life_time_earning'] = $bookingInfo->grand_total ?? 0;
        if ($bookingInfo->seller_id != null) {
            $adminData['total_profit'] = $bookingInfo->tax ?? 0;
        } else {
            $adminData['total_profit'] = $bookingInfo->grand_total ?? 0;
        }

        storeEarnings($adminData);

        $bookingInfo['transaction_type'] = 1;
        storeTransaction($bookingInfo);

        $orderProcess->prepareMail($bookingInfo);
        $orderProcess->prepareMailForVendor($bookingInfo);
    }
}
