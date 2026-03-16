<?php

use Illuminate\Support\Facades\Route;

// ===== Push Notification =====

Route::post('/push-notification/store-endpoint', 'FrontEnd\PushNotificationController@store');


// ===== Cron Jobs =====

Route::get('/process-bookings', 'CronJobController@processBooking')->name('process.bookings');

Route::get('/subcheck', 'CronJobController@expired')->name('cron.expired');
Route::get('/check-payment', 'CronJobController@checkPayment')->name('cron.check_payment');


// ===== Payment Gateway Notifications =====

// Midtrans Bank
Route::prefix('/midtrans')->group(function () {
  Route::get('/bank/notify', 'MidtransBankNotifyController@onlineBankNotify')->name('midtrans.bank_notify');
  Route::get('/cancel', 'MidtransBankNotifyController@cancel')->name('midtrans.cancel');
});

// MyFatoorah
Route::get('myfatoorah/callback', 'MyFatoorahController@myfatoorah_callback')->name('myfatoorah_callback');
Route::get('myfatoorah/cancel', 'MyFatoorahController@myfatoorah_cancel')->name('myfatoorah_cancel');


// ===== Language and Subscriber =====

Route::get('/change-language', 'FrontEnd\MiscellaneousController@changeLanguage')->name('change_language');
Route::post('/store-subscriber', 'FrontEnd\MiscellaneousController@storeSubscriber')->name('store_subscriber');
Route::post('/subscriber', 'FrontEnd\SubscriberController@store')->name('store.subscriber')->middleware('change.lang');


// ===== Frontend Routes (with 'change.lang' middleware) =====

Route::middleware('change.lang')->group(function () {

  Route::get('/', 'FrontEnd\HomeController@index')->name('index');
  Route::get('/spaces', 'FrontEnd\SpaceController@index')->name('space.index');
  Route::get('/search-space', 'FrontEnd\SpaceController@search_space')->name('frontend.space.search');
  Route::post('/space/update-wishlist/{id?}/{slug?}', 'FrontEnd\SpaceController@updateWishlist')->name('space.update.wishlist');
  Route::get('/details/{slug?}/{id?}', 'FrontEnd\SpaceController@spaceDetails')->name('space.details');
  Route::get('/get-time-schedule', 'FrontEnd\BookingProcessController@getTimeSlotsByDate')->name('frontend.booking.get_time_slot');
  Route::post('/store-selected-items/{slug?}', 'FrontEnd\BookingProcessController@getBookingData')->name('confirm.booking');
  Route::get('/confirm-booking', 'FrontEnd\BookingProcessController@index')->name('frontend.booking.checkout.index');
  Route::post('/apply-coupon', 'FrontEnd\BookingProcessController@applyCoupon')->name('frontend.coupon.data');
  Route::post('/space/{id}/store-review', 'FrontEnd\SpaceController@storeReview')->name('space.review.store')->middleware('demo');

  // Space filtering
  Route::get('/fetch-space-date', 'FrontEnd\SpaceController@fetchSpaceData')->name('frontend.space.filter.fetch_space_data');
  Route::get('/get-states-by-country', 'FrontEnd\SpaceController@getStatesByCountry')->name('frontend.space.filter.get_states_by_country');
  Route::get('/get-cities-by-state', 'FrontEnd\SpaceController@getCitiesByState')->name('frontend.space.filter.get_cities_by_state');
  Route::get('/pricing', 'FrontEnd\HomeController@pricing')->name('pricing');

  // Nested booking routes with prefix and grouped payment notifies
  Route::prefix('/space/{slug?}/booking')->middleware(['demo'])->group(function () {
    Route::post('', 'FrontEnd\OrderProcessController@index')->name('service.place_order');
    Route::post('get-quote-information', 'FrontEnd\OrderProcessController@getQuoteInfo')->name('frontend.space.booking.get_quote_info');
    Route::post('book-a-tour-information', 'FrontEnd\OrderProcessController@bookForTourInfo')->name('frontend.space.booking.book_a_info');

    // Payment notifies
    Route::get('/toyyibpay/notify', 'FrontEnd\PaymentGateway\ToyyibpayController@notify')->name('service.place_order.toyyibpay.notify');
    Route::get('/phonepe/notify', 'FrontEnd\PaymentGateway\PhonePeController@notify')->name('service.place_order.phonepe.notify');
    Route::any('/freshpay/notify', 'FrontEnd\PaymentGateway\FreshpayController@notify')->name('service.place_order.freshpay.notify');
    Route::get('/xendit/notify', 'FrontEnd\PaymentGateway\XenditController@notify')->name('service.place_order.xendit.notify');
    Route::get('/yoco/notify', 'FrontEnd\PaymentGateway\YocoController@notify')->name('service.place_order.yoco.notify');
    Route::get('/paypal/notify', 'FrontEnd\PaymentGateway\PayPalController@notify')->name('service.place_order.paypal.notify');
    Route::get('/instamojo/notify', 'FrontEnd\PaymentGateway\InstamojoController@notify')->name('service.place_order.instamojo.notify');
    Route::get('/paystack/notify', 'FrontEnd\PaymentGateway\PaystackController@notify')->name('service.place_order.paystack.notify');
    Route::get('/flutterwave/notify', 'FrontEnd\PaymentGateway\FlutterwaveController@notify')->name('service.place_order.flutterwave.notify');
    Route::post('/razorpay/notify', 'FrontEnd\PaymentGateway\RazorpayController@notify')->name('service.place_order.razorpay.notify');
    Route::get('/mercadopago/notify', 'FrontEnd\PaymentGateway\MercadoPagoController@notify')->name('service.place_order.mercadopago.notify');
    Route::get('/mollie/notify', 'FrontEnd\PaymentGateway\MollieController@notify')->name('service.place_order.mollie.notify');
    Route::post('/paytm/notify', 'FrontEnd\PaymentGateway\PaytmController@notify')->name('service.place_order.paytm.notify');
    Route::post('/iyzico/notify', 'FrontEnd\PaymentGateway\IyzicoController@notify')->name('service.place_order.iyzico.notify');
    Route::get('/perfect-money/notify', 'FrontEnd\PaymentGateway\PerfectMoneyController@notify')->name('service.place_order.perfect_money.notify');
    Route::post('/paytabs/notify', 'FrontEnd\PaymentGateway\PaytabsController@notify')->name('service.place_order.paytabs.notify');

    Route::get('/complete', 'FrontEnd\OrderProcessController@complete')->name('service.place_order.complete');
    Route::get('/cancel', 'FrontEnd\OrderProcessController@cancel')->name('service.place_order.cancel');
  });

  // Other payment gateway callbacks (outside space booking)
  Route::get('/midtrans/notify/{id}', 'FrontEnd\PaymentGateway\MidtransController@cardNotify')->name('service.place_order.midtrans.notify');
  Route::get('booking/myfatoorah/callback', 'FrontEnd\PaymentGateway\MyFatoorahController@notify')->name('myfatoorah_callback_for_booking');
  Route::get('/booking/myfatoorah/cancel', 'FrontEnd\PaymentGateway\MyFatoorahController@cancel')->name('myfatoorah_cancel_for_booking');


  // Products & Shop routes with middlewares
  Route::middleware(['shop.status'])->group(function () {
    Route::get('shop/products', 'FrontEnd\Shop\ProductController@index')->name('shop.products');

    Route::prefix('shop/product')->group(function () {
      Route::get('/{slug?}', 'FrontEnd\Shop\ProductController@show')->name('shop.product_details');
      Route::get('/{id}/add-to-cart/{quantity}', 'FrontEnd\Shop\ProductController@addToCart')->name('shop.product.add_to_cart');
    });

    Route::prefix('/shop')->group(function () {
      Route::get('/cart', 'FrontEnd\Shop\ProductController@cart')->name('shop.cart');
      Route::post('/update-cart', 'FrontEnd\Shop\ProductController@updateCart')->name('shop.update_cart');
      Route::get('/cart/remove-product/{id}', 'FrontEnd\Shop\ProductController@removeProduct')->name('shop.cart.remove_product');
      Route::get('put-shipping-method-id/{id}', 'FrontEnd\Shop\ProductController@put_shipping_method')->name('put-shipping-method-id');

      Route::prefix('/checkout')->group(function () {
        Route::get('', 'FrontEnd\Shop\ProductController@checkout')->name('shop.checkout');
        Route::post('/apply-coupon', 'FrontEnd\Shop\ProductController@applyCoupon');
        Route::get('/offline-gateway/{id}/check-attachment', 'FrontEnd\Shop\ProductController@checkAttachment');
      });

      Route::prefix('/purchase-product')->middleware(['demo'])->group(function () {
        Route::post('', 'FrontEnd\Shop\PurchaseProcessController@index')->name('shop.purchase_product');

        // Payment notifies grouped
        Route::get('/paypal/notify', 'FrontEnd\Shop\PaymentGateway\PayPalController@notify')->name('shop.purchase_product.paypal.notify');
        Route::get('/instamojo/notify', 'FrontEnd\Shop\PaymentGateway\InstamojoController@notify')->name('shop.purchase_product.instamojo.notify');
        Route::get('/paystack/notify', 'FrontEnd\Shop\PaymentGateway\PaystackController@notify')->name('shop.purchase_product.paystack.notify');
        Route::get('/flutterwave/notify', 'FrontEnd\Shop\PaymentGateway\FlutterwaveController@notify')->name('shop.purchase_product.flutterwave.notify');
        Route::post('/razorpay/notify', 'FrontEnd\Shop\PaymentGateway\RazorpayController@notify')->name('shop.purchase_product.razorpay.notify');
        Route::get('/mercadopago/notify', 'FrontEnd\Shop\PaymentGateway\MercadoPagoController@notify')->name('shop.purchase_product.mercadopago.notify');
        Route::get('/mollie/notify', 'FrontEnd\Shop\PaymentGateway\MollieController@notify')->name('shop.purchase_product.mollie.notify');
        Route::get('/midtrans/notify', 'FrontEnd\Shop\PaymentGateway\MidtransController@creditCardNotify')->name('shop.midtrans.notify');
        Route::post('/paytm/notify', 'FrontEnd\Shop\PaymentGateway\PaytmController@notify')->name('shop.purchase_product.paytm.notify');
        Route::post('/iyzico/notify', 'FrontEnd\Shop\PaymentGateway\IyzipayController@notify')->name('shop.purchase_product.iyzico.notify');
        Route::get('/xendit/notify', 'FrontEnd\Shop\PaymentGateway\XenditController@notify')->name('shop.purchase_product.xendit.notify');
        Route::post('xendit/callback', 'FrontEnd\Shop\PaymentGateway\XenditController@xendit_callback')->name('xendit_cancel');
        Route::get('/yoco/notify', 'FrontEnd\Shop\PaymentGateway\YocoController@notify')->name('shop.purchase_product.yoco.notify');
        Route::any('/phonepe/notify', 'FrontEnd\Shop\PaymentGateway\PhonepeController@notify')->name('shop.purchase_product.phonepe.notify');
        Route::any('/freshpay/notify', 'FrontEnd\Shop\PaymentGateway\FreshpayController@notify')->name('shop.purchase_product.freshpay.notify');
        Route::get('/toyyibpay/notify', 'FrontEnd\Shop\PaymentGateway\ToyyibpayController@notify')->name('shop.purchase_product.toyyibpay.notify');
        Route::post('/paytabs/notify', 'FrontEnd\Shop\PaymentGateway\PaytabsController@notify')->name('shop.purchase_product.paytabs.notify');

        Route::get('/perfect-money/notify', 'FrontEnd\Shop\PaymentGateway\PerfectMoneyController@notify')->name('shop.purchase_product.perfect-money.notify');
        Route::get('/perfect-money/cancel', 'FrontEnd\Shop\PaymentGateway\PerfectMoneyController@cancel')->name('shop.purchase_product.perfect-money.cancel');

        Route::get('/complete/{type?}', 'FrontEnd\Shop\PurchaseProcessController@complete')->name('shop.purchase_product.complete')->middleware('change.lang');
        Route::get('/cancel', 'FrontEnd\Shop\PurchaseProcessController@cancel')->name('shop.purchase_product.cancel');
      });

      Route::post('/product/{id}/store-review', 'FrontEnd\Shop\ProductController@storeReview')->name('shop.product_details.store_review');
    });
  });

  // Vendor routes
  Route::prefix('vendors')->group(function () {
    Route::get('/', 'FrontEnd\SellerController@index')->name('frontend.sellers');
    Route::post('contact/message', 'FrontEnd\SellerController@contact')->name('seller.contact.message')->middleware('demo');
  });
  Route::get('vendor-details', 'FrontEnd\SellerController@details')->name('frontend.seller.details');

  // Blog routes
  Route::prefix('/blog')->group(function () {
    Route::get('', 'FrontEnd\BlogController@index')->name('blog');
    Route::get('/post/{slug?}/{id?}', 'FrontEnd\BlogController@show')->name('blog.post_details');
  });

  Route::get('/about-us', 'FrontEnd\AboutUsController@index')->name('about_us');
  Route::get('/faq', 'FrontEnd\FaqController@faq')->name('faq');

  // Contact form routes
  Route::prefix('/contact')->group(function () {
    Route::get('', 'FrontEnd\ContactController@contact')->name('contact');
    Route::post('/send-mail', 'FrontEnd\ContactController@sendMail')->name('contact.send_mail')->middleware('demo');
  });
});


// Advertisement view count (no middleware)
Route::post('/advertisement/{id}/count-view', 'FrontEnd\MiscellaneousController@countAdView');

// Social login callbacks (no middleware)
Route::get('login/facebook/callback', 'FrontEnd\UserController@handleFacebookCallback');
Route::get('login/google/callback', 'FrontEnd\UserController@handleGoogleCallback');

// ===== User Authentication Routes =====

// Guest routes
Route::prefix('/user')->middleware(['guest:web', 'change.lang'])->group(function () {
  Route::prefix('/login')->group(function () {
    Route::get('', 'FrontEnd\UserController@login')->name('user.login');
    Route::get('/facebook', 'FrontEnd\UserController@redirectToFacebook')->name('user.login.facebook');
    Route::get('/google', 'FrontEnd\UserController@redirectToGoogle')->name('user.login.google');
  });

  Route::post('/login-submit', 'FrontEnd\UserController@loginSubmit')->name('user.login_submit');

  Route::get('/forget-password', 'FrontEnd\UserController@forgetPassword')->name('user.forget_password');
  Route::post('/send-forget-password-mail', 'FrontEnd\UserController@forgetPasswordMail')->name('user.send_forget_password_mail')->middleware('demo');

  Route::get('/reset-password', 'FrontEnd\UserController@resetPassword');
  Route::post('/reset-password-submit', 'FrontEnd\UserController@resetPasswordSubmit')->name('user.reset_password_submit')->middleware('demo');

  Route::get('/signup', 'FrontEnd\UserController@signup')->name('user.signup');
  Route::post('/signup-submit', 'FrontEnd\UserController@signupSubmit')->name('user.signup_submit')->middleware('demo');
  Route::get('/signup-verify/{token}', 'FrontEnd\UserController@signupVerify');
});

// Authenticated user routes
Route::prefix('/user')->middleware(['auth:web', 'account.status', 'change.lang'])->group(function () {
  Route::get('/dashboard', 'FrontEnd\UserController@redirectToDashboard')->name('user.dashboard');
  Route::get('/edit-profile', 'FrontEnd\UserController@editProfile')->name('user.edit_profile');
  Route::post('/update-profile', 'FrontEnd\UserController@updateProfile')->name('user.update_profile');

  Route::middleware('exists.password')->group(function () {
    Route::get('/change-password', 'FrontEnd\UserController@changePassword')->name('user.change_password');
    Route::post('/update-password', 'FrontEnd\UserController@updatePassword')->name('user.update_password');
  });

  // User orders - shop status middleware already applied at controller level
  Route::prefix('/product')->group(function () {
    Route::get('order-record', 'FrontEnd\Shop\OrderController@index')->name('user.order.index')->middleware('shop.status');
    Route::get('/order/details/{id}', 'FrontEnd\Shop\OrderController@details')->name('user.order.details')->middleware('shop.status');
  });

  // Space booking routes for user
  Route::prefix('/space-booking')->group(function () {
    Route::get('/', 'FrontEnd\UserController@spaceBooking')->name('user.space_bookings');
    Route::get('/details/{id}', 'FrontEnd\UserController@spaceBookingDetails')->name('frontend.user.space-booking-details');
  });

  // Space wishlist routes
  Route::prefix('/space-wishlist')->group(function () {
    Route::get('', 'FrontEnd\UserController@spaceWishlist')->name('user.space_wishlist');
    Route::post('/remove-space-wishlist/{space_id}', 'FrontEnd\UserController@removeSpaecWishlisted')->name('user.space_wishlist.remove');
    Route::post('download/{product_id}', 'FrontEnd\Shop\OrderController@download')->name('user.product_order.product.download')->middleware('shop.status');
  });

  Route::get('/logout', 'FrontEnd\UserController@logoutSubmit')->name('user.logout');
});

// dataload per page for country, state, city
Route::get('/countries', 'FrontEnd\LocationController@getCountries')->name('web.get_countries');
Route::get('/states', 'FrontEnd\LocationController@getStates')->name('web.get_states');
Route::get('/cities', 'FrontEnd\LocationController@getCities')->name('web.get_cities');

// ===== Miscellaneous =====

Route::get('/service-unavailable', 'FrontEnd\MiscellaneousController@serviceUnavailable')->name('service_unavailable')->middleware('exists.down');

// ===== Admin Routes =====

Route::prefix('/admin')->middleware(['guest:admin', 'demo'])->group(function () {
  Route::get('/', 'Admin\AdminController@login')->name('admin.account.login')->withoutMiddleware('demo');
  Route::post('/auth', 'Admin\AdminController@authentication')->name('admin.auth');
  Route::get('/forget-password', 'Admin\AdminController@forgetPassword')->name('admin.account.forget_password');
  Route::post('/mail-for-forget-password', 'Admin\AdminController@forgetPasswordMail')->name('admin.mail_for_forget_password');
});

// ===== Dynamic Custom Page Routes =====

Route::get('/{slug}', 'FrontEnd\PageController@page')->name('dynamic_page')->middleware('change.lang');

// ===== Fallback Route =====

Route::fallback(function () {
  return view('errors.404');
})->middleware('change.lang');
