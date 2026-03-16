<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Interface Routes
|--------------------------------------------------------------------------
*/

Route::get('/set-locale-vendor', 'Vendor\VendorController@setLocaleVendor')->name('set-Locale-vendor');
Route::any('/vendor/membership/freshpay/notify', 'Payment\FreshpayController@notify')->name('membership.freshpay.notify');

Route::prefix('vendor')
  ->middleware(['vendorLang', 'guest:seller', 'demo'])
  ->group(function () {
    Route::get('/signup', 'Vendor\VendorController@signup')->name('vendor.signup');
    Route::post('/signup/submit', 'Vendor\VendorController@create')->name('vendor.signup_submit')->middleware('change.lang');
    Route::get('/login', 'Vendor\VendorController@login')->name('vendor.login')->middleware('change.lang');
    Route::post('/login/submit', 'Vendor\VendorController@authentication')->name('vendor.login_submit')->withoutMiddleware('demo')->middleware('change.lang');
    Route::get('/email/verify', 'Vendor\VendorController@confirm_email')->name('vendor.account.verify');
    Route::get('/forget-password', 'Vendor\VendorController@forget_passord')->name('vendor.forget.password')->middleware('change.lang');
    Route::post('/send-forget-mail', 'Vendor\VendorController@forget_mail')->name('vendor.forget.mail');
    Route::get('/reset-password', 'Vendor\VendorController@reset_password')->name('vendor.reset.password');
    Route::post('/update-forget-password', 'Vendor\VendorController@update_password')->name('vendor.update_forget_password');
  });

Route::prefix('vendor')
  ->middleware(['auth:seller', 'vendorLang', 'EmailStatus:seller', 'Deactive:seller' , 'demo'])
  ->group(function () {
    Route::get('dashboard', 'Vendor\VendorController@dashboard')->name('vendor.dashboard');
    Route::get('monthly-income', 'Vendor\VendorController@monthly_income')->name('vendor.monthly_income');
    Route::get('/change-password', 'Vendor\VendorController@change_password')->name('vendor.change_password');
    Route::post('/update-password', 'Vendor\VendorController@updated_password')->name('vendor.update_password');
    Route::get('/edit-profile', 'Vendor\VendorController@edit_profile')->name('vendor.edit.profile');
    Route::post('/profile/update', 'Vendor\VendorController@update_profile')->name('vendor.update_profile');
    Route::get('/logout', 'Vendor\VendorController@logout')->name('vendor.logout');
    Route::get('/recipient-mail', 'Vendor\VendorController@recipient_mail')->name('vendor.recipient_mail');
    Route::post('/update/recipient-mail', 'Vendor\VendorController@update_recipient_mail')->name('vendor.update_recipient_mail')->middleware('downgrade:withAjax');

    // change vendor-panel theme (dark/light) route
    Route::post('/change-theme', 'Vendor\VendorController@changeTheme')->name('vendor.change_theme')->withoutMiddleware('demo');

    // space form route start here
    Route::get('/forms', 'Vendor\FormController@index')->name('vendor.space_management.form.index');
    Route::post('/store-form', 'Vendor\FormController@store')->name('vendor.space_management.form.store');

    Route::prefix('/form')->group(function () {
      Route::get('/{id}/input', 'Vendor\FormInputController@manageInput')->name('vendor.space_management.form-input.index');
      Route::post('/{id}/store-input', 'Vendor\FormInputController@storeInput')->name('vendor.space_management.form-input.store');
      Route::get('/{form_id}/edit-input/{input_id}', 'Vendor\FormInputController@editInput')->name('vendor.space_management.form-input.edit');
      Route::post('/update-input/{id}', 'Vendor\FormInputController@updateInput')->name('vendor.space_management.form-input.update');
      Route::post('/delete-input/{id}', 'Vendor\FormInputController@destroyInput')->name('vendor.space_management.form-input.delete');
      Route::post('/sort-input', 'Vendor\FormInputController@sortInput')->name('vendor.space_management.form-input.sort_input');
    });

    Route::post('/update-form', 'Vendor\FormController@update')->name('vendor.space_management.form-input.update_form');
    Route::post('/delete-form/{id}', 'Vendor\FormController@destroy')->name('vendor.space_management.form-input.delete_form');

    //  space management route section start

    Route::prefix('space-management')
      ->middleware(['downgrade:withAjax'])
      ->group(function () {

        Route::get('/spaces', 'Vendor\VendorSpaceController@index')->name('vendor.space_management.space.index');
        Route::get('/select-space-type', 'Vendor\VendorSpaceController@spaceType')->name('vendor.space_management.space.select_space_type')->withoutMiddleware(['downgrade:withAjax']);
        Route::get('/create-space', 'Vendor\VendorSpaceController@create')->name('vendor.space_management.space.create')->withoutMiddleware(['downgrade:withAjax']);
        Route::post('/upload-slider-image', 'Vendor\VendorSpaceController@uploadImage')->name('vendor.space_management.space.upload_slider_image');
        Route::post('/remove-slider-image', 'Vendor\VendorSpaceController@removeImage')->name('vendor.space_management.space.remove_slider_image');
        Route::post('/store-space', 'Vendor\VendorSpaceController@store')->name('vendor.space_management.space.store')->middleware('limit_check:space');
        Route::get('/edit-space/{id}', 'Vendor\VendorSpaceController@edit')->name('vendor.space_management.space.edit')->middleware('limit_check:space');
        Route::post('/detach-slider-image', 'Vendor\VendorSpaceController@detachImage')->name('vendor.space_management.detach_slider_image');
        Route::post('/delete-amenity', 'Vendor\VendorSpaceController@deleteAmenity')->name('vendor.space_management.space.delete-amenity');
        Route::post('/update-space/{id}', 'Vendor\VendorSpaceController@update')->name('vendor.space_management.space.update');
        Route::post('/delete-space/{id}', 'Vendor\VendorSpaceController@destroy')->name('vendor.space_management.space.delete');
        Route::post('/bulk-delete-space', 'Vendor\VendorSpaceController@bulkDestroy')->name('vendor.space_management.space.bulk_delete');

        Route::get('/states-by-country-for-space', 'Vendor\VendorSpaceController@getStatesByCountryForSpace')->name('vendor.location_management.space.states_by_country');
        Route::get('/cities-by-country-or-states', 'Vendor\VendorSpaceController@getCitiesByCountryForSpace')->name('vendor.location_management.space.cities_by_country_or_state');
        Route::get('get-subcategory', 'Vendor\VendorSpaceController@getSpaceSubcategories')->name('vendor.space_management.get_subcategory');

        // space service route start here
        Route::get('/view-services/{space_id?}', 'Vendor\VendorSpaceServiceController@viewService')->name('vendor.space_management.service.view_from_space');
        Route::post('/Store-service', 'Vendor\VendorSpaceServiceController@store')->name('vendor.space_management.service.store')->middleware('limit_check:service-per-space');
        Route::get('/edit-service/{id?}', 'Vendor\VendorSpaceServiceController@edit')->name('vendor.space_management.service.edit');
        Route::post('/update-service', 'Vendor\VendorSpaceServiceController@update')->name('vendor.space_management.update_space_service');
        Route::post('/delete-service/{id}', 'Vendor\VendorSpaceServiceController@destroy')->name('vendor.space_management.service.delete');
        Route::post('/bulk-delete-category', 'Vendor\VendorSpaceServiceController@bulkDestroy')->name('vendor.space_management.service.bulk_delete');
        Route::get('/create-service-partially', 'Vendor\VendorSpaceServiceController@partialCreate')->name('vendor.space_management.service.create_under_space');
        Route::post('/subservice-delete', 'Vendor\VendorSpaceServiceController@deleteStoredSubService')->name('vendor.space_management.service.stored_sub_service_delete');
        Route::post('/image-remove', 'Vendor\VendorSpaceServiceController@removeImage')->name('vendor.subservice.image.remove');

        // space coupon route start
        Route::get('/coupons', 'Vendor\VendorCouponController@index')->name('vendor.space_management.coupons.index');
        Route::post('/store-coupon', 'Vendor\VendorCouponController@store')->name('vendor.space_management.coupons.store');
        Route::post('/update-coupon', 'Vendor\VendorCouponController@update')->name('vendor.space_management.coupons.update');
        Route::post('/delete-coupon/{id}', 'Vendor\VendorCouponController@destroy')->name('vendor.space_management.coupons.delete');
      });
    Route::get('vendor/coupon-data', 'Vendor\VendorCouponController@getCouponData')->name('vendor.coupon.data');
    Route::get('/get-space', 'Vendor\VendorCouponController@getSpaceForCoupon')->name('vendor.coupon.get_space');


    //  space management route end

    //   quote and tour request route start
    Route::get('/quote/request', 'Vendor\QuoteRequestController@index')->name('vendor.space.form.get_quote.index');
    Route::post('/quote/request-update/{id}', 'Vendor\QuoteRequestController@update')->name('vendor.quote-request.status.update');
    Route::post('/delete-quote', 'Vendor\QuoteRequestController@destroy')->name('vendor.quote-request.delete');
    Route::post('/bulk-delete-quote-request', 'Vendor\QuoteRequestController@bulkDestroy')->name('vendor.quote-request.bulk_delete');
    Route::get('/tour/request', 'Vendor\TourRequestController@index')->name('vendor.space.form.tour_request.index');
    Route::post('/tour/request-update/{id}', 'Vendor\TourRequestController@update')->name('vendor.tour-request.status.update');
    Route::post('/delete-tour', 'Vendor\TourRequestController@destroy')->name('vendor.tour-request.delete');
    Route::post('/bulk-delete-tour-request', 'Vendor\TourRequestController@bulkDestroy')->name('vendor.tour-request.bulk_delete');
    //   quote and tour request route end 

    // this route for holiday 
    Route::prefix('holiday')->group(function () {
      Route::get('/list', 'Vendor\VendorHolidayController@index')->name('vendor.holiday.index');
      Route::post('/holiday-date-store', 'Vendor\VendorHolidayController@store')->name('vendor.holiday.store');
      Route::post('/delete/{id}', 'Vendor\VendorHolidayController@destroy')->name('vendor.space.holiday.delete');
      Route::post('/bulke-destory', 'Vendor\VendorHolidayController@blukDestroy')->name('vendor.space.holiday.bluk_destroy');
    });

    //  Route for Booking Management start

    Route::prefix('/booking-records')->group(function () {
      // Booking record routes
      Route::get('', 'Vendor\BookingRecordController@index')
        ->name('vendor.booking_record.index');

      Route::post('/delete', 'Vendor\BookingRecordController@destroy')
        ->name('vendor.booking_record.delete');

      Route::post('/bulk-delete', 'Vendor\BookingRecordController@bulkDestroy')
        ->name('vendor.booking_record.bulk_delete');

      Route::get('/details/{id}', 'Vendor\BookingRecordController@show')
        ->name('vendor.booking_record.show');

      Route::get('/report', 'Vendor\BookingRecordController@bookingReport')
        ->name('vendor.booking_record.booking_report');

      Route::get('/export-report', 'Vendor\BookingRecordController@exportReport')
        ->name('vendor.booking_record.export_report');

      Route::post('/send-mail/{id?}', 'Vendor\BookingRecordController@sendMail')
        ->name('vendor.booking_record.sendmail');

      // Add booking routes with middleware
      Route::middleware(['addBookingPermission'])->group(function () {
        Route::get('/space-selection', 'Vendor\AddBookingController@spaceSelect')
          ->name('vendor.add_booking.space_selection');

        Route::get('/get-space', 'Vendor\AddBookingController@getSpaceForAddBooking')
          ->name('vendor.add_booking.get_space');

        Route::get('/add-booking', 'Vendor\AddBookingController@index')
          ->name('vendor.add_booking.index')
          ->middleware('downgrade:withoutAjax');

        Route::post('/store-selected-items/{slug?}', 'Vendor\AddBookingController@storeBookingData')
          ->name('vendor.confirm.booking')
          ->middleware('downgrade:withAjax');

        Route::get('/get-time-schedule', 'Vendor\AddBookingController@getTimeSlotsByDate')
          ->name('vendor.booking.get_time_slot');
      });
    });

    //  Route for Booking Management end

    // Vendor package extend routes
    Route::get('/package-list', 'Vendor\BuyPlanController@index')
      ->name('vendor.plan.extend.index');

    Route::get('/package/checkout/{package_id}', 'Vendor\BuyPlanController@checkout')
      ->name('vendor.plan.extend.checkout');

    Route::post('/package/checkout', 'Vendor\VendorCheckoutController@checkout')
      ->name('vendor.plan.checkout')->middleware('demo');

    Route::post('/payment/instructions', 'Vendor\VendorCheckoutController@paymentInstruction')
      ->name('vendor.payment.instructions');

    // Vendor subscription log route
    Route::get('/subscription-log', 'Vendor\VendorController@subscription_log')
      ->name('vendor.subscription_log');

    //checkout payment gateway routes
    Route::prefix('membership')->group(function () {

      Route::get('paypal/success', "Payment\PaypalController@successPayment")->name('membership.paypal.success');
      Route::get('paypal/cancel', "Payment\PaypalController@cancelPayment")->name('membership.paypal.cancel');
      Route::get('stripe/cancel', "Payment\StripeController@cancelPayment")->name('membership.stripe.cancel');
      Route::post('paytm/payment-status', "Payment\PaytmController@paymentStatus")->name('membership.paytm.status');
      Route::get('paystack/success', 'Payment\PaystackController@successPayment')->name('membership.paystack.success');
      Route::post('paystack/cancel', 'Payment\InstamojoController@cancelPayment')->name('membership.paystack.cancel');
      Route::get('mercadopago/cancel', 'Payment\MercadopagoController@cancelPayment')->name('membership.mercadopago.cancel');
      Route::get('mercadopago/success', 'Payment\MercadopagoController@successPayment')->name('membership.mercadopago.success');
      Route::post('razorpay/success', 'Payment\RazorpayController@successPayment')->name('membership.razorpay.success');
      Route::post('razorpay/cancel', 'Payment\RazorpayController@cancelPayment')->name('membership.razorpay.cancel');
      Route::get('instamojo/success', 'Payment\InstamojoController@successPayment')->name('membership.instamojo.success');
      Route::post('instamojo/cancel', 'Payment\InstamojoController@cancelPayment')->name('membership.instamojo.cancel');
      Route::post('flutterwave/success', 'Payment\FlutterWaveController@successPayment')->name('membership.flutterwave.success');
      Route::post('flutterwave/cancel', 'Payment\FlutterWaveController@cancelPayment')->name('membership.flutterwave.cancel');
      Route::get('/mollie/success', 'Payment\MollieController@successPayment')->name('membership.mollie.success');
      Route::post('mollie/cancel', 'Payment\MollieController@cancelPayment')->name('membership.mollie.cancel');
      Route::get('anet/cancel', 'Payment\AuthorizeController@cancelPayment')->name('membership.anet.cancel');
      Route::get('/midtrans/success/{id}', 'Payment\MidtransController@cardNotify')->name('membership.midtrans.success');
      Route::get('midtrans/cancel', 'Payment\MidtransController@cancelPayment')->name('membership.midtrans.cancel');
      Route::get('/xendit/success', 'Payment\XenditController@successPayment')->name('membership.xendit.success');
      Route::get('xendit/cancel', 'Payment\XenditController@cancelPayment')->name('membership.xendit.cancel');
      Route::post('/iyzico/success', 'Payment\IyzicoController@successPayment')->name('membership.iyzico.success');
      Route::get('iyzico/cancel', 'Payment\IyzicoController@cancelPayment')->name('membership.iyzico.cancel');
      Route::any('/yoco/success', 'Payment\YocoController@successPayment')->name('membership.yoco.success');
      Route::get('yoco/cancel', 'Payment\YocoController@cancelPayment')->name('membership.yoco.cancel');
      Route::get('/toyyibpay/success', 'Payment\ToyyibpayController@successPayment')->name('membership.toyyibpay.success');
      Route::get('toyyibpay/cancel', 'Payment\ToyyibpayController@cancelPayment')->name('membership.toyyibpay.cancel');
      Route::any('/freshpay/success', 'Payment\FreshpayController@successPayment')->name('membership.freshpay.success');
      Route::get('freshpay/cancel', 'Payment\FreshpayController@cancelPayment')->name('membership.freshpay.cancel');
      Route::post('/paytabs/success', 'Payment\PaytabsController@successPayment')->name('membership.paytabs.success');
      Route::get('paytabs/cancel', 'Payment\PaytabsController@cancelPayment')->name('membership.paytabs.cancel');
      Route::any('/phonepe/success', 'Payment\PhonePeController@successPayment')->name('membership.phonepe.success');
      Route::get('phonepe/cancel', 'Payment\PhonePeController@cancelPayment')->name('membership.phonepe.cancel');
      Route::get('/perfect_money/success', 'Payment\PerfectMoneyController@successPayment')->name('membership.perfect_money.success');
      Route::get('perfect_money/cancel', 'Payment\PerfectMoneyController@cancelPayment')->name('membership.perfect_money.cancel');
      Route::get('/offline/success', 'Front\CheckoutController@offlineSuccess')->name('membership.offline.success');
      Route::get('/trial/success', 'Front\CheckoutController@trialSuccess')->name('membership.trial.success');
    });
    Route::get('/online-payment/success', 'Vendor\VendorCheckoutController@onlineSuccess')->name('success.page');
    Route::get('/offline/success/{type}', 'Vendor\VendorCheckoutController@offlineSuccess')->name('vendor.offline-success');

    Route::get('/transaction', 'Vendor\VendorController@transcation')->name('vendor.transaction');

    //start manage schedule route
    Route::prefix('/manage-schedule')->group(function () {
      Route::get('/{id?}', 'Vendor\TimeSlotController@index')
        ->name('vendor.manage_schedule.time_slot.index');

      Route::get('/weekend/{space_id?}', 'Vendor\TimeSlotController@manageWeekend')
        ->name('vendor.manage_weekend.index');

      Route::post('/weekend/{id?}', 'Vendor\TimeSlotController@updateWeekend')
        ->name('vendor.manage_schedule.time_slot.update_weekend')
        ->middleware(['downgrade:withoutAjax']);

      Route::get('/time-slot/{dayId}/{spaceId?}', 'Vendor\TimeSlotController@manageSchedule')
        ->name('vendor.manage_schedule.time_slot.manage_time_slot');

      Route::post('/store', 'Vendor\TimeSlotController@storeTimeSlot')
        ->name('vendor.manage_schedule.time_slot.store')
        ->middleware(['downgrade:withAjax']);

      Route::post('/update', 'Vendor\TimeSlotController@update')
        ->name('vendor.manage_schedule.time_slot.update')
        ->middleware(['downgrade:withAjax']);

      Route::post('/delete', 'Vendor\TimeSlotController@destroy')
        ->name('vendor.manage_schedule.time_slot.delete');

      Route::post('/bulk-delete', 'Vendor\TimeSlotController@bulkDestroy')
        ->name('vendor.manage_schedule.time_slot.bulk_destroy');
    });
    //end manage schedule route

    Route::prefix('withdraw')->group(function () {
      Route::get('/', 'Vendor\VendorWithdrawController@index')
        ->name('vendor.withdraw');

      Route::get('/create', 'Vendor\VendorWithdrawController@create')
        ->name('vendor.withdraw.create')
        ->middleware('downgrade:withoutAjax');

      Route::get('/get-method/input/{id}', 'Vendor\VendorWithdrawController@get_inputs');

      Route::get('/balance-calculation/{method}/{amount}', 'Vendor\VendorWithdrawController@balance_calculation');

      Route::post('/send-request', 'Vendor\VendorWithdrawController@send_request')
        ->name('vendor.withdraw.send-request')
        ->middleware('downgrade:withAjax');

      Route::post('/withdraw/bulk-delete', 'Vendor\VendorWithdrawController@bulkDelete')
        ->name('vendor.withdraw.bulk_delete_withdraw');

      Route::post('/withdraw/delete', 'Vendor\VendorWithdrawController@Delete')
        ->name('vendor.withdraw.delete_withdraw');
    });

    #====support tickets ============

    Route::prefix('support/ticket')
      ->middleware(['isSupportTicket'])
      ->group(function () {
        // View and create tickets
        Route::get('/support-ticket', 'Vendor\SupportTicketController@index')
          ->name('vendor.support_ticket');

        Route::get('create', 'Vendor\SupportTicketController@create')
          ->name('vendor.support_ticket.create');

        Route::post('store', 'Vendor\SupportTicketController@store')
          ->name('vendor.support_ticket.store')
          ->middleware('downgrade:withoutAjax');

        Route::get('message/{id}', 'Vendor\SupportTicketController@message')
          ->name('vendor.support_ticket.message');

        // File uploads
        Route::post('zip-upload', 'Vendor\SupportTicketController@zip_file_upload')
          ->name('seller.support_ticket.zip_file.upload');

        // Ticket replies
        Route::post('reply/{id}', 'Vendor\SupportTicketController@ticketreply')
          ->name('vendor.support_ticket.reply');

        // Deletion
        Route::post('delete/{id}', 'Vendor\SupportTicketController@delete')
          ->name('vendor.support_tickets.delete');

        Route::post('bulk/delete', 'Vendor\SupportTicketController@bulk_delete')
          ->name('vendor.support_tickets.bulk_delete');
      });

  Route::get('/countries', 'Vendor\LocationController@getCountries')->name('vendor.get_countries');
  Route::get('/states', 'Vendor\LocationController@getStates')->name('vendor.get_states');
  Route::get('/cities', 'Vendor\LocationController@getCities')->name('vendor.get_cities');
  });
