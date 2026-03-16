<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Interface Routes
|--------------------------------------------------------------------------
*/

Route::get('/set-locale-admin', 'Admin\BasicSettings\BasicController@setLocaleAdmin')->name('set-Locale-admin');
Route::prefix('/admin')
  ->middleware(['auth:admin', 'adminLang' , 'demo'])
  ->group(function () {

    // admin  dashboard related  route start
    Route::get('/dashboard', 'Admin\AdminController@redirectToDashboard')->name('admin.dashboard');

    // change admin-panel theme (dark/light) route
    Route::get('/change-theme', 'Admin\AdminController@changeTheme')->name('admin.change_theme');
    Route::get('/monthly-profit', 'Admin\AdminController@monthly_profit')->name('admin.dashboard.monthly_profit');
    Route::get('/monthly-earning', 'Admin\AdminController@monthly_earning')->name('admin.dashboard.monthly_earning');
    // admin  dashboard related  route end

    // admin account related  route start
    Route::get('/edit-profile', 'Admin\AdminController@editProfile')->name('admin.account.edit_profile');
    Route::post('/update-profile', 'Admin\AdminController@updateProfile')->name('admin.account.update_profile');
    Route::get('/change-password', 'Admin\AdminController@changePassword')->name('admin.account.change_password');
    Route::post('/update-password', 'Admin\AdminController@updatePassword')->name('admin.account.update_password');
    Route::get('/logout', 'Admin\AdminController@logout')->name('admin.account.logout');
    // admin account related  route start

    // space management route start
    Route::prefix('/space-management')
      ->middleware(['permission:Space Management,Space Settings,Holidays,Coupons,Featured Management,Forms,Spaces'])
      ->group(function () {
        // Space Settings Routes
        Route::prefix('/settings')
          ->middleware(['permission:Space Settings'])
          ->group(function () {
            Route::get('', 'Admin\space\SpaceController@settings')->name('admin.space-management.space.settings');
            Route::post('/update', 'Admin\space\SpaceController@settingsUpdate')->name('admin.space-management.space.settings.update');
          });

        // Holiday Management Routes
        Route::prefix('/holiday')
          ->middleware(['permission:Holidays'])
          ->group(function () {
            Route::get('/select-vendor', 'Admin\AdminHolidayController@sellerSelect')->name('admin.holiday.select_vendor');
            Route::get('/list', 'Admin\AdminHolidayController@index')->name('admin.holiday.index');
            Route::post('/holiday-date-store', 'Admin\AdminHolidayController@store')->name('admin.holiday.store');
            Route::post('/delete/{id}', 'Admin\AdminHolidayController@destroy')->name('admin.space.holiday.delete');
            Route::post('/bulke-destory', 'Admin\AdminHolidayController@blukDestroy')->name('admin.space.holiday.bluk_destroy');
          });

        // Coupon Management Routes
        Route::prefix('/coupons')
          ->middleware(['permission:Coupons'])
          ->group(function () {
            Route::get('', 'Admin\space\AdminCouponController@index')->name('admin.space_management.coupons.index');
            Route::post('/store-coupon', 'Admin\space\AdminCouponController@store')->name('admin.space_management.coupons.store');
            Route::post('/update-coupon', 'Admin\space\AdminCouponController@update')->name('admin.space_management.coupons.update');
            Route::post('/delete-coupon/{id}', 'Admin\space\AdminCouponController@destroy')->name('admin.space_management.coupons.delete');
          });

        // Feature Records Management Routes
        Route::prefix('/feature-records')
          ->middleware(['permission:Featured Management'])
          ->group(function () {
            // Featured Charge Routes
            Route::prefix('/charge')->group(function () {
              Route::get('', 'Admin\SpaceFeatureManagement\FeaturedChargeController@index')->name('admin.feature_record.charge.index');
              Route::get('/create', 'Admin\SpaceFeatureManagement\FeaturedChargeController@create')->name('admin.feature_record.charge.create');
              Route::post('/store', 'Admin\SpaceFeatureManagement\FeaturedChargeController@store')->name('admin.feature_record.charge.store');
              Route::post('/update', 'Admin\SpaceFeatureManagement\FeaturedChargeController@update')->name('admin.feature_record.charge.update');
              Route::post('/delete', 'Admin\SpaceFeatureManagement\FeaturedChargeController@destroy')->name('admin.feature_record.charge.delete');
              Route::post('/bulk-delete', 'Admin\SpaceFeatureManagement\FeaturedChargeController@bulkDestroy')->name('admin.feature_record.charge.bulk_delete');
            });

            // Feature Management Routes
            Route::get('/all-request', 'Admin\SpaceFeatureManagement\FeatureManagementController@index')->name('admin.feature_record.index');
            Route::post('/update-feature-booking-status/{id}', 'Admin\SpaceFeatureManagement\FeatureManagementController@updateBookingStatus')->name('admin.feature_record.update_booking_status');
            Route::post('/update-payment-status/{id}', 'Admin\SpaceFeatureManagement\FeatureManagementController@updatePaymentStatus')->name('admin.feature_record.update_payment_status');
            Route::post('/bulk-delete', 'Admin\SpaceFeatureManagement\FeatureManagementController@bulkDestroy')->name('admin.feature_record.bulk_delete');
            Route::post('/delete', 'Admin\SpaceFeatureManagement\FeatureManagementController@destroy')->name('admin.feature_record.delete');
          });

        // Space Management Routes start
        Route::prefix('/spaces')
          ->middleware(['permission:Spaces'])
          ->group(function () {
            Route::get('', 'Admin\space\SpaceController@index')->name('admin.space_management.space.index');
            Route::get('/select/vendor', 'Admin\space\SpaceController@sellerSelect')->name('admin.space_management.seller_select');
            Route::get('/create', 'Admin\space\SpaceController@create')->name('admin.space_management.space.create');
            Route::post('/store', 'Admin\space\SpaceController@store')
              ->name('admin.space_management.space.store')
              ->middleware('limit_check:space', 'demo');
            Route::post('/delete-amenity', 'Admin\space\SpaceController@deleteAmenity')->name('admin.space_management.space.delete-amenity');
            Route::get('/edit/{id}', 'Admin\space\SpaceController@edit')->name('admin.space_management.space.edit');
            Route::post('/update/{id}', 'Admin\space\SpaceController@update')->name('admin.space_management.space.update');
            Route::post('/delete/{id}', 'Admin\space\SpaceController@destroy')->name('admin.space_management.space.delete');
            Route::post('/bulk-delete', 'Admin\space\SpaceController@bulkDestroy')->name('admin.space_management.space.bulk_delete');

            // Featured Status Routes
            Route::post('/{id}/update-featured-status', 'Admin\space\SpaceController@updateFeaturedStatus')
              ->name('admin.space_management.space.update_featured_status');
            Route::get('/unfeature/{id}', 'Admin\space\SpaceController@unfeatureSpace')
              ->name('admin.space_management.space.update_featured_status');
            Route::post('/feature', 'Admin\space\SpaceController@checkout')
              ->name('admin.space_management.space.checkout_for_featured_status');

            // Image Management Routes
            Route::post('/upload-slider-image', 'Admin\space\SpaceController@uploadImage')
              ->name('admin.space_management.space.upload_slider_image');
            Route::post('/remove-slider-image', 'Admin\space\SpaceController@removeImage')
              ->name('admin.space_management.space.remove_slider_image');
            Route::post('/detach-slider-image', 'Admin\space\SpaceController@detachImage')
              ->name('admin.space_management.space.detach_slider_image');
          });

        // Form  Routes start here
        Route::prefix('/forms')
          ->middleware(['permission:Forms'])
          ->group(function () {

            Route::get('', 'Admin\FormController@index')->name('admin.space-management.form.index');
            Route::post('/store-form', 'Admin\FormController@store')->name('admin.space-management.form.store');
            Route::post('/update-form', 'Admin\FormController@update')->name('admin.space-management.update_form');
            Route::post('/delete-form/{id}', 'Admin\FormController@destroy')->name('admin.space-management.delete_form');

            // Form Input Management Routes
            Route::prefix('/form')->group(function () {
              Route::get('/{id}/input', 'Admin\FormInputController@manageInput')->name('admin.space-management.form.input');
              Route::post('/{id}/store-input', 'Admin\FormInputController@storeInput')->name('admin.space-management.form.store_input');
              Route::get('/{form_id}/edit-input/{input_id}', 'Admin\FormInputController@editInput')
                ->name('admin.space-management.form.edit_input');
              Route::post('/update-input/{id}', 'Admin\FormInputController@updateInput')
                ->name('admin.space-management.form.update_input');
              Route::post('/delete-input/{id}', 'Admin\FormInputController@destroyInput')
                ->name('admin.space-management.form.delete_input');
              Route::post('/sort-input', 'Admin\FormInputController@sortInput')
                ->name('admin.space-management.form.sort_input');
            });
          });

        // Service Management Routes
        Route::prefix('/services')
          ->middleware(['permission:Spaces'])
          ->group(function () {
            // Service CRUD Operations
            Route::get('/view/space-id/{space_id?}', 'Admin\space\ServiceController@viewService')
              ->name('admin.space_management.service.view_services');
            Route::get('/create-partially', 'Admin\space\ServiceController@partialCreate')
              ->name('admin.space_management.service.partial_create');
            Route::post('/store', 'Admin\space\ServiceController@store')
              ->name('admin.space_management.service.store')
              ->middleware('limit_check:service-per-space', 'demo');
            Route::get('/edit/space-id/{space_id}/service-id/{id}', 'Admin\space\ServiceController@edit')
              ->name('admin.space_management.service.edit');
            Route::post('/update', 'Admin\space\ServiceController@update')
              ->name('admin.space_management.service.update');
            Route::post('/delete/{id}', 'Admin\space\ServiceController@destroy')
              ->name('admin.space_management.service.delete');
            Route::post('/bulk-delete', 'Admin\space\ServiceController@bulkDestroy')
              ->name('admin.space_management.service.bulk_delete');

            // Subservice Operations
            Route::post('/subservice/delete', 'Admin\space\ServiceController@deleteStoredSubService')
              ->name('admin.space_management.service.stored_sub_service_delete');
            Route::post('/image/remove', 'Vendor\VendorSpaceServiceController@removeImage')
              ->name('admin.subservice.image.remove');
          });

        // Schedule Management Routes
        Route::prefix('/manage-schedule')
          ->middleware(['permission:Spaces'])
          ->group(function () {
            // Main Schedule Routes
            Route::get('{id?}', 'Admin\space\ManageScheduleController@index')
              ->name('admin.manage_schedule.time_slot.index');

            // Weekend Management Routes
            Route::prefix('/weekend')->group(function () {
              Route::get('{space_id?}', 'Admin\space\ManageScheduleController@manageWeekend')
                ->name('admin.manage_weekend.index');
              Route::post('{id?}', 'Admin\space\ManageScheduleController@updateWeekend')
                ->name('admin.manage_schedule.time_slot.update_weekend');
            });

            // Time Slot Management Routes
            Route::prefix('/time-slot')->group(function () {
              Route::get('/{dayId}/{spaceId?}', 'Admin\space\ManageScheduleController@manageSchedule')
                ->name('admin.manage_schedule.time_slot.manage_time_slot');
              Route::post('/store', 'Admin\space\ManageScheduleController@storeTimeSlot')
                ->name('admin.manage_schedule.time_slot.store');
              Route::post('/update', 'Admin\space\ManageScheduleController@update')
                ->name('admin.manage_schedule.time_slot.update');
              Route::post('/delete', 'Admin\space\ManageScheduleController@destroy')
                ->name('admin.manage_schedule.time_slot.delete');
              Route::post('/bulk-delete', 'Admin\space\ManageScheduleController@bulkDestroy')
                ->name('admin.manage_schedule.time_slot.bulk_destroy');
            });
          });
      });

    Route::prefix('/space-management/specification')
      ->middleware(['permission:Space Management,Specifications'])
      ->group(function () {
        // Amenities routes
        Route::get('/amenities', 'Admin\space\SpaceAmenityController@index')->name('admin.space_management.amenities.index');
        Route::post('/amenities/store', 'Admin\space\SpaceAmenityController@store')->name('admin.space_management.amenities.store');
        Route::post('/amenities/update', 'Admin\space\SpaceAmenityController@update')->name('admin.space_management.amenities.update');
        Route::post('/amenities/delete', 'Admin\space\SpaceAmenityController@delete')->name('admin.space_management.amenities.delete');
        Route::post('/amenities/bulk-delete', 'Admin\space\SpaceAmenityController@bulkDestroy')->name('admin.space_management.amenities.bulk_delete');

        // Location Management routes 
        Route::prefix('/location-management')
          ->group(function () {
            Route::get('/countries', 'Admin\LocationManagementController@indexCountry')->name('admin.location_management.country.index');
            Route::post('/store-country', 'Admin\LocationManagementController@storeCountry')->name('admin.location_management.country.store');
            Route::post('/update-country', 'Admin\LocationManagementController@updateCountry')->name('admin.location_management.country.update');
            Route::post('/delete-country', 'Admin\LocationManagementController@destroyCountry')->name('admin.location_management.country.destroy');
            Route::post('/bulk-delete-country', 'Admin\LocationManagementController@bulkDestroyCountry')->name('admin.location_management.country.bulk_delete');

            Route::get('/states', 'Admin\LocationManagementController@indexState')->name('admin.location_management.state.index');
            Route::post('/store-state', 'Admin\LocationManagementController@storeState')->name('admin.location_management.state.store');
            Route::post('/update-state', 'Admin\LocationManagementController@updateState')->name('admin.location_management.state.update');
            Route::post('/delete-state', 'Admin\LocationManagementController@destroyState')->name('admin.location_management.state.destroy');
            Route::post('/bulk-delete-state', 'Admin\LocationManagementController@bulkDestroyState')->name('admin.location_management.state.bulk_delete');
            Route::get('/get-countries-data', 'Admin\LocationManagementController@getCountries')->name('admin.location_management.get_country_data');

            Route::get('/cities', 'Admin\LocationManagementController@indexCity')->name('admin.location_management.city.index');
            Route::post('/store-city', 'Admin\LocationManagementController@storeCity')->name('admin.location_management.city.store');
            Route::post('/update-city', 'Admin\LocationManagementController@updateCity')->name('admin.location_management.city.update');
            Route::post('/delete-city', 'Admin\LocationManagementController@destroyCity')->name('admin.location_management.city.destroy');
            Route::post('/bulk-delete-city', 'Admin\LocationManagementController@bulkDestroyCity')->name('admin.location_management.city.bulk_delete');
            Route::get('/get-states-by-country', 'Admin\LocationManagementController@getStatesByCountry')->name('admin.location_management.city.get_states_by_country');
            Route::get('/states-by-country-for-space', 'Admin\LocationManagementController@getStatesByCountryForSpace')->name('admin.location_management.space.states_by_country');
            Route::get('/cities-by-country-or-states', 'Admin\LocationManagementController@getCitiesByCountryForSpace')->name('admin.location_management.space.cities_by_country_or_state');
            Route::post('/city/{id}/update-featured-status', 'Admin\LocationManagementController@updateCityFeaturedStatus')->name('admin.location_management.city.feature_update');
          });

        // Categories routes
        Route::get('/categories', 'Admin\space\AdminSpaceCategoryController@index')->name('admin.space_management.space-category.index');
        Route::post('/store-space-category', 'Admin\space\AdminSpaceCategoryController@store')->name('admin.space_management.space-category.store');
        Route::post('/category/{id}/update-featured-status', 'Admin\space\AdminSpaceCategoryController@updateFeaturedStatus')->name('admin.space_management.space-category.feature_update');
        Route::post('/update-category', 'Admin\space\AdminSpaceCategoryController@update')->name('admin.space_management.space-category.update');
        Route::post('/delete-category/{id}', 'Admin\space\AdminSpaceCategoryController@destroy')->name('admin.space_management.space-category.destroy');
        Route::post('/bulk-delete-space-category', 'Admin\space\AdminSpaceCategoryController@bulkDestroy')->name('admin.space_management.space-category.bulk_delete');

        // Subcategories routes
        Route::get('/space/sub-categories', 'Admin\space\AdminSpaceSubCategoryController@index')->name('admin.space_management.sub-category.index');
        Route::post('space/subcategory-store', 'Admin\space\AdminSpaceSubCategoryController@store')->name('admin.space_management.sub-category.store');
        Route::post('space/subcategory-update', 'Admin\space\AdminSpaceSubCategoryController@update')->name('admin.space_management.sub-category.update');
        Route::post('space/subcategory-delete/{id}', 'Admin\space\AdminSpaceSubCategoryController@destroy')->name('admin.space_management.sub-category.destroy');
        Route::post('space/bulk-delete-subcategory', 'Admin\space\AdminSpaceSubCategoryController@bulkDestroy')->name('admin.space_management.sub-category.bulk_delete');
        Route::get('/get-categories', 'Admin\space\AdminSpaceSubCategoryController@getCategories')->name('admin.space_management.get-category');
      });
    // space management route end

    // Bookings & Requests Management Routes start
    Route::prefix('/bookings-requests')
      ->middleware(['permission:Bookings & Requests,Quote Requests,Tour Requests,Booking Management'])
      ->group(function () {
        // Form Request Routes
        Route::prefix('/form')
          ->middleware(['permission:Quote Requests,Tour Requests'])
          ->group(function () {
            // Quote Request Management
            Route::prefix('/quote')->group(function () {
              Route::get('/request', 'Admin\QuoteRequestController@index')
                ->name('admin.space.form.get_quote.index');
              Route::post('/request-update/{id}', 'Admin\QuoteRequestController@update')
                ->name('admin.quote-request.status.update');
              Route::post('/delete-quote', 'Admin\QuoteRequestController@destroy')
                ->name('admin.quote-request.delete');
              Route::post('/bulk-delete-quote-request', 'Admin\QuoteRequestController@bulkDestroy')
                ->name('admin.quote-request.bulk_delete');
            });

            // Tour Request Management
            Route::prefix('/tour')
              ->middleware(['permission:Tour Requests'])
              ->group(function () {
                Route::get('/request', 'Admin\TourRequestController@index')
                  ->name('admin.space.form.tour_request.index');
                Route::post('/request-update/{id}', 'Admin\TourRequestController@update')
                  ->name('admin.tour-request.status.update');
                Route::post('/delete-tour', 'Admin\TourRequestController@destroy')
                  ->name('admin.tour-request.delete');
                Route::post('/bulk-delete-tour-request', 'Admin\TourRequestController@bulkDestroy')
                  ->name('admin.tour-request.bulk_delete');
              });
          });

        // Booking Records Management
        Route::prefix('/booking-records')
          ->middleware(['permission:Booking Management'])
          ->group(function () {
            // Booking Data Routes
            Route::get('/get-time-schedule', 'Admin\AddBookingController@getTimeSlotsByDate')
              ->name('admin.booking.get_time_slot');
            Route::post('/store-selected-items/{slug?}', 'Admin\AddBookingController@storeBookingData')
              ->name('admin.confirm.booking');
            Route::get('/space-selection', 'Admin\AddBookingController@spaceSelect')
              ->name('admin.add_booking.space_selection');
            Route::get('/get-space', 'Admin\AddBookingController@getSpaceForAddBooking')
              ->name('admin.add_booking.get_space');
            Route::get('/add-booking', 'Admin\AddBookingController@index')
              ->name('admin.add_booking.index');

            // Booking Management Routes
            Route::get('', 'Admin\BookingManagementController@index')
              ->name('admin.booking_record.index');
            Route::post('/delete', 'Admin\BookingManagementController@destroy')
              ->name('admin.booking_record.delete');
            Route::post('/update-booking-status/{id}', 'Admin\BookingManagementController@updateBookingStatus')
              ->name('admin.booking_record.update_booking_status');
            Route::post('/update-payment-status/{id}', 'Admin\BookingManagementController@updatePaymentStatus')
              ->name('admin.booking_record.update_payment_status');
            Route::post('/bulk-delete', 'Admin\BookingManagementController@bulkDestroy')
              ->name('admin.booking_record.bulk_delete');
            Route::get('/details/{id}', 'Admin\BookingManagementController@show')
              ->name('admin.booking_record.show');

            // Report Routes
            Route::get('/report', 'Admin\BookingManagementController@bookingReport')
              ->name('admin.booking_record.booking_report');
            Route::get('/export-report', 'Admin\BookingManagementController@exportReport')
              ->name('admin.booking_record.export_report');
            Route::post('/send-mail/{id}', 'Admin\BookingManagementController@sendMail')
              ->name('admin.booking_record.sendmail');
          });
      });

    // Bookings & Requests Management Routes end

    // User Management Routes start
    Route::prefix('/user-management')
      ->middleware('permission:User Management,Registered Users,Subscribers')
      ->group(function () {

        // Registered User Management
        Route::prefix('/users')
          ->middleware('permission:Registered Users')
          ->group(function () {

            // user Settings Routes
            Route::prefix('/settings')
              ->middleware(['permission:User Settings'])
              ->group(function () {
                Route::get('', 'Admin\CustomerManagement\UserController@settings')->name('admin.user_management.registered_users.setting');
                Route::post('/update', 'Admin\CustomerManagement\UserController@settingUpdated')->name('admin.user_management.register_user.setting.update');
              });

            Route::get('/registered', 'Admin\CustomerManagement\UserController@index')
              ->name('admin.user_management.registered_users');
            Route::post('/register', 'Admin\CustomerManagement\UserController@registerUser')
              ->name('admin.user_management.register_user');
            Route::post('/bulk-delete', 'Admin\CustomerManagement\UserController@bulkDestroy')
              ->name('admin.user_management.bulk_delete_user');


            // Individual User Operations
            Route::prefix('/user/{id?}')->group(function () {
              Route::get('/details', 'Admin\CustomerManagement\UserController@show')
                ->name('admin.user_management.user.details');
              Route::get('/edit', 'Admin\CustomerManagement\UserController@edit')
                ->name('admin.user_management.user.edit');
              Route::post('/update', 'Admin\CustomerManagement\UserController@update')
                ->name('admin.user_management.user.update');

              // Status Management
              Route::post('/update-email-status', 'Admin\CustomerManagement\UserController@updateEmailStatus')
                ->name('admin.user_management.user.update_email_status');
              Route::post('/update-account-status', 'Admin\CustomerManagement\UserController@updateAccountStatus')
                ->name('admin.user_management.user.update_account_status');

              // Password Management
              Route::get('/change-password', 'Admin\CustomerManagement\UserController@changePassword')
                ->name('admin.user_management.user.change_password');
              Route::post('/update-password', 'Admin\CustomerManagement\UserController@updatePassword')
                ->name('admin.user_management.user.update_password');

              // Security Operations
              Route::post('/secretLogin', 'Admin\CustomerManagement\UserController@secretLogin')
                ->name('admin.user_management.user.secretLogin');
              Route::post('/delete', 'Admin\CustomerManagement\UserController@destroy')
                ->name('admin.user_management.user.delete');
            });
          });
        // User Management Routes end

        // Subscriber Management
        Route::prefix('/subscribers')
          ->middleware('permission:Subscribers')
          ->group(function () {
            Route::get('', 'Admin\CustomerManagement\SubscriberController@index')
              ->name('admin.user_management.subscribers');
            Route::post('/{id}/delete', 'Admin\CustomerManagement\SubscriberController@destroy')
              ->name('admin.user_management.subscriber.delete');
            Route::post('/bulk-delete', 'Admin\CustomerManagement\SubscriberController@bulkDestroy')
              ->name('admin.user_management.bulk_delete_subscriber');

            // Subscriber Email Operations
            Route::get('/mail', 'Admin\CustomerManagement\SubscriberController@writeEmail')
              ->name('admin.user_management.mail_for_subscribers');
            Route::post('/send-email', 'Admin\CustomerManagement\SubscriberController@prepareEmail')
              ->name('admin.user_management.subscribers.send_email');
          });
      });

    // Vendor Management Routes start
    Route::prefix('/vendor-management')
      ->middleware(['permission:Vendors Management,Vendor Settings,Add Vendor,Registered Vendors'])
      ->group(function () {

        Route::get('/registered-vendors', 'Admin\VendorManagementController@index')
          ->name('admin.end-user.vendor.registered_vendor')->middleware(['permission:Registered Vendors']);

        // Vendor Settings Routes
        Route::prefix('/settings')
          ->middleware(['permission:Vendor Settings'])
          ->group(function () {
            Route::get('', 'Admin\VendorManagementController@settings')
              ->name('admin.end-user.vendor.settings');
            Route::post('/update', 'Admin\VendorManagementController@update_setting')
              ->name('admin.end-user.vendor.setting.update');
          });

        // Vendor CRUD Operations
        Route::prefix('/vendor')
          ->middleware(['permission:Add Vendor'])
          ->group(function () {
            Route::get('/add-vendor', 'Admin\VendorManagementController@create')
              ->name('admin.end-user.vendor.add');
            Route::post('/save-vendor', 'Admin\VendorManagementController@store')
              ->name('admin.end-user.vendor.add.save');
            Route::post('/bulk-delete-vendor', 'Admin\VendorManagementController@bulkDestroy')
              ->name('admin.end-user.vendor.bulk_delete');
          });

        // Individual Vendor Operations
        Route::prefix('/vendor/{id}')
          ->middleware(['permission:Registered Vendors'])
          ->group(function () {
            // Vendor Status Management
            Route::post('/update-account-status', 'Admin\VendorManagementController@updateAccountStatus')
              ->name('admin.end-user.vendor.update_account_status');
            Route::post('/update-email-status', 'Admin\VendorManagementController@updateEmailStatus')
              ->name('admin.end-user.vendor.update_email_status');

            // Vendor Profile Management
            Route::get('/details', 'Admin\VendorManagementController@show')
              ->name('admin.end-user.vendor.details');
            Route::get('/edit', 'Admin\VendorManagementController@edit')
              ->name('admin.end-user.vendor.edit');
            Route::post('/update', 'Admin\VendorManagementController@update')
              ->name('admin.end-user.vendor.update_vendor');

            // Vendor Password Management
            Route::get('/change-password', 'Admin\VendorManagementController@changePassword')
              ->name('admin.end-user.vendor.change_password');
            Route::post('/update-password', 'Admin\VendorManagementController@updatePassword')
              ->name('admin.end-user.vendor.update_password');

            // Vendor Financial Operations
            Route::post('/update/vendor/balance', 'Admin\VendorManagementController@update_seller_balance')
              ->name('admin.end-user.vendor.update_vendor_balance');
            Route::post('/delete', 'Admin\VendorManagementController@destroy')
              ->name('admin.end-user.vendor.delete');
          });

        // Vendor Package Management
        Route::prefix('/packages')
          ->middleware(['permission:Registered Vendors'])
          ->group(function () {
            // Current Package Operations
            Route::post('/current/remove', 'Admin\VendorManagementController@removeCurrPackage')
              ->name('admin.end-user.vendor.currPackage_remove');
            Route::post('/current/change', 'Admin\VendorManagementController@changeCurrPackage')
              ->name('admin.end-user.vendor.currPackage_change');
            Route::post('/current/add', 'Admin\VendorManagementController@addCurrPackage')
              ->name('admin.end-user.vendor.currPackage_add');

            // Next Package Operations
            Route::post('/next/remove', 'Admin\VendorManagementController@removeNextPackage')
              ->name('admin.end-user.vendor.nextPackage_remove');
            Route::post('/next/change', 'Admin\VendorManagementController@changeNextPackage')
              ->name('admin.end-user.vendor.nextPackage_change');
            Route::post('/next/add', 'Admin\VendorManagementController@addNextPackage')
              ->name('admin.end-user.vendor.nextPackage_add');
          });

        // Vendor Security Operations
        Route::get('/secret-login/{id}', 'Admin\VendorManagementController@secret_login')
          ->name('admin.end-user.vendor.secret_login');
      });

    // Vendor Management Routes end

    // Subscriptions Management Routes start
    Route::prefix('/subscriptions')
      ->middleware(['permission:Subscriptions Management,Package Management,Subscription Logs'])
      ->group(function () {

        // Package Settings
        Route::prefix('/settings')
          ->middleware(['permission:Package Management'])
          ->group(function () {
            Route::get('', 'Admin\PackageController@settings')
              ->name('admin.package.settings');
            Route::post('', 'Admin\PackageController@updateSettings')
              ->name('admin.package.settings');
          });

        // Package CRUD Operations
        Route::prefix('/packages')
          ->middleware(['permission:Package Management'])
          ->group(function () {
            Route::get('', 'Admin\PackageController@index')
              ->name('admin.package.index');
            Route::post('/store', 'Admin\PackageController@store')
              ->name('admin.package.store');
            Route::get('/{id}/edit', 'Admin\PackageController@edit')
              ->name('admin.package.edit');
            Route::post('/update', 'Admin\PackageController@update')
              ->name('admin.package.update');
            Route::post('/delete', 'Admin\PackageController@delete')
              ->name('admin.package.delete');
            Route::post('/bulk-delete', 'Admin\PackageController@bulkDelete')
              ->name('admin.package.bulk.delete');

            // Package Media Handling
            Route::post('/upload', 'Admin\PackageController@upload')
              ->name('admin.package.upload');
            Route::post('/{id}/uploadUpdate', 'Admin\PackageController@uploadUpdate')
              ->name('admin.package.uploadUpdate');

            // Package Features
            Route::get('/features', 'Admin\PackageController@features')
              ->name('admin.package.features');
            Route::post('/features/update', 'Admin\PackageController@updateFeatures')
              ->name('admin.package.features.update');
          });

        // Subscription Logs
        Route::prefix('/subscription')
          ->middleware(['permission:Package Management,Subscription Logs'])
          ->group(function () {
            Route::get('/log', 'Admin\PaymentLogController@index')
              ->name('admin.payment-log.index');
            Route::post('/log/update', 'Admin\PaymentLogController@update')
              ->name('admin.payment-log.update');
          });
      });
    // Subscriptions Management Routes end

    // Withdrawals Management Routes start
    Route::prefix('withdraw')
      ->middleware(['permission:Withdrawal Management,Payment Methods,Withdraw Requests'])
      ->group(function () {

        // Payment Methods Management
        Route::prefix('/payment-methods')
          ->middleware(['permission:Payment Methods'])
          ->group(function () {
            // CRUD Operations
            Route::get('', 'Admin\Withdraw\WithdrawPaymentMethodController@index')
              ->name('admin.withdraw.payment_method');
            Route::post('/store', 'Admin\Withdraw\WithdrawPaymentMethodController@store')
              ->name('admin.withdraw_payment_method.store');
            Route::put('/update', 'Admin\Withdraw\WithdrawPaymentMethodController@update')
              ->name('admin.withdraw_payment_method.update');
            Route::post('/delete/{id}', 'Admin\Withdraw\WithdrawPaymentMethodController@destroy')
              ->name('admin.withdraw_payment_method.delete');

            // Payment Method Inputs Management
            Route::prefix('/inputs')->group(function () {
              Route::get('', 'Admin\Withdraw\WithdrawPaymentMethodInputController@index')
                ->name('admin.withdraw_payment_method.mange_input');
              Route::post('/store', 'Admin\Withdraw\WithdrawPaymentMethodInputController@store')
                ->name('admin.withdraw_payment_method.store_input');
              Route::get('/edit/{id}', 'Admin\Withdraw\WithdrawPaymentMethodInputController@edit')
                ->name('admin.withdraw_payment_method.edit_input');
              Route::post('/update', 'Admin\Withdraw\WithdrawPaymentMethodInputController@update')
                ->name('admin.withdraw_payment_method.update_input');
              Route::post('/order-update', 'Admin\Withdraw\WithdrawPaymentMethodInputController@order_update')
                ->name('admin.withdraw_payment_method.order_update');
              Route::get('/options/{id}', 'Admin\Withdraw\WithdrawPaymentMethodInputController@get_options')
                ->name('admin.withdraw_payment_method.options');
              Route::post('/delete', 'Admin\Withdraw\WithdrawPaymentMethodInputController@delete')
                ->name('admin.withdraw_payment_method.options_delete');
            });
          });

        // Withdraw Requests Management
        Route::prefix('/requests')
          ->middleware(['permission:Withdraw Requests'])
          ->group(function () {
            Route::get('', 'Admin\Withdraw\WithdrawController@index')
              ->name('admin.withdraw.withdraw_request');
            Route::post('/delete', 'Admin\Withdraw\WithdrawController@delete')
              ->name('admin.witdraw.delete_withdraw');
            Route::get('/approve/{id}', 'Admin\Withdraw\WithdrawController@approve')
              ->name('admin.witdraw.approve_withdraw');
            Route::get('/decline/{id}', 'Admin\Withdraw\WithdrawController@decline')
              ->name('admin.witdraw.decline_withdraw');
          });
      });
    // Withdrawals Management Routes end

    // Pages Management Routes
    Route::prefix('/pages')
      ->middleware(['permission:Pages,Home Page,About Us,Menu Builder,Footer,Breadcrumbs,FAQs,Blogs,Contact Page,Additional Pages,SEO Information'])
      ->group(function () {

        // Home Page Management
        Route::prefix('/home-page')
          ->middleware(['permission:Home Page'])
          ->group(function () {

            // Section Customization
            Route::get('/section-customization', 'Admin\HomePage\SectionController@index')
              ->name('admin.home_page.section_customization');
            Route::post('/update-section-status', 'Admin\HomePage\SectionController@update')
              ->name('admin.home_page.update_section_status');

            // Images & Texts Content
            Route::get('/images-&-texts', 'Admin\HomePage\SectionController@sectionContent')
              ->name('admin.home_page.section_content');
            Route::post('/update/images-&-texts', 'Admin\HomePage\SectionController@updateContent')
              ->name('admin.home_page.section_content_update');

            // Work Process Section
            Route::prefix('/work-process-section')->group(function () {
              Route::get('', 'Admin\HomePage\WorkProcessController@index')
                ->name('admin.home_page.work_process_section');
              Route::post('/update-background-image', 'Admin\HomePage\WorkProcessController@updateBgImg')
                ->name('admin.home_page.update_features_bg');
              Route::post('/store-work-prosess', 'Admin\HomePage\WorkProcessController@storeWorkProcess')
                ->name('admin.home_page.store_feature');
              Route::post('/update-feature', 'Admin\HomePage\WorkProcessController@updateWorkProcess')
                ->name('admin.home_page.update_feature');
              Route::post('/delete-feature/{id}', 'Admin\HomePage\WorkProcessController@destroyWorkProcess')
                ->name('admin.home_page.delete_feature');
              Route::post('/bulk-delete-feature', 'Admin\HomePage\WorkProcessController@bulkDestroyWorkProcess')
                ->name('admin.home_page.bulk_delete_feature');
            });

            // Testimonials Section
            Route::prefix('/testimonials-section')->group(function () {
              Route::get('', 'Admin\HomePage\TestimonialController@index')
                ->name('admin.home_page.testimonials_section');
              Route::post('/update-background-image', 'Admin\HomePage\TestimonialController@updateBgImg')
                ->name('admin.home_page.update_testimonials_bg');
              Route::post('/store-testimonial', 'Admin\HomePage\TestimonialController@storeTestimonial')
                ->name('admin.home_page.store_testimonial');
              Route::post('/update-testimonial', 'Admin\HomePage\TestimonialController@updateTestimonial')
                ->name('admin.home_page.update_testimonial');
              Route::post('/delete-testimonial/{id}', 'Admin\HomePage\TestimonialController@destroyTestimonial')
                ->name('admin.home_page.delete_testimonial');
              Route::post('/bulk-delete-testimonial', 'Admin\HomePage\TestimonialController@bulkDestroyTestimonial')
                ->name('admin.home_page.bulk_delete_testimonial');
            });

            // Additional Sections
            Route::prefix('/additional-sections')->group(function () {
              Route::get('sections', 'Admin\AdditionSectionController@index')
                ->name('admin.home_page.additional_sections.index');
              Route::get('add-section', 'Admin\AdditionSectionController@create')
                ->name('admin.home_page.additional_sections.create');
              Route::post('store-section', 'Admin\AdditionSectionController@store')
                ->name('admin.home_page.additional_section.store');
              Route::get('edit-section/{id}', 'Admin\AdditionSectionController@edit')
                ->name('admin.home_page.additional_section.edit');
              Route::post('update/{id}', 'Admin\AdditionSectionController@update')
                ->name('admin.home_page.additional_section.update');
              Route::post('delete/{id}', 'Admin\AdditionSectionController@delete')
                ->name('admin.home_page.additional_section.delete');
              Route::post('bulkdelete', 'Admin\AdditionSectionController@bulkdelete')
                ->name('admin.home_page.additional_section.bulkdelete');
            });
          });

        // About Content Section Routes
        Route::prefix('/about-content-section')
          ->middleware(['permission:About Us'])
          ->group(function () {
            // About Section Heading & Image/Info Management
            Route::get('', 'Admin\AboutUs\AboutController@heading')->name('admin.home_page.about_section');
            Route::post('/update-image', 'Admin\AboutUs\AboutController@updateImage')->name('admin.home_page.update_about_img');
            Route::post('/update-info', 'Admin\AboutUs\AboutController@updateInfo')->name('admin.home_page.update_about_info');

            // About Content Management
            Route::get('/about-content', 'Admin\AboutUs\AboutContentController@index')->name('admin.about_us.about_content.index');
            Route::get('/create', 'Admin\AboutUs\AboutContentController@create')->name('admin.home_page.about_content.create');
            Route::get('/edit', 'Admin\AboutUs\AboutContentController@edit')->name('admin.home_page.about_content.edit');
            Route::post('/store', 'Admin\AboutUs\AboutContentController@store')->name('admin.home_page.about_content.store');
            Route::post('/update-sub-info', 'Admin\AboutUs\AboutContentController@update')->name('admin.home_page.update_about_sub_info');
            Route::post('/delete-about-content/{id}', 'Admin\AboutUs\AboutContentController@destroy')->name('admin.home_page.about_content.delete');
            Route::post('/bulk-delete-about-content', 'Admin\AboutUs\AboutContentController@bulkDestroy')->name('admin.home_page.about_content.bulk_delete');
          });

        // menu-builder route 
        Route::prefix('/menu-builder')
          ->middleware(['permission:Menu Builder'])
          ->group(function () {
            Route::get('', 'Admin\MenuBuilderController@index')->name('admin.menu_builder');
            Route::post('/update-menus', 'Admin\MenuBuilderController@update')->name('admin.menu_builder.update_menus');
          });

        // footer route 
        Route::prefix('/footer')
          ->middleware(['permission:Footer'])
          ->group(function () {
            // content route
            Route::get('/content', 'Admin\Footer\ContentController@index')->name('admin.footer.content');
            Route::post('/update-content', 'Admin\Footer\ContentController@update')->name('admin.footer.update_content');

            // quick link route
            Route::get('/quick-links', 'Admin\Footer\QuickLinkController@index')->name('admin.footer.quick_links');
            Route::post('/store-quick-link', 'Admin\Footer\QuickLinkController@store')->name('admin.footer.store_quick_link');
            Route::post('/update-quick-link', 'Admin\Footer\QuickLinkController@update')->name('admin.footer.update_quick_link');
            Route::post('/delete-quick-link/{id}', 'Admin\Footer\QuickLinkController@destroy')->name('admin.footer.delete_quick_link');
          });

        // Breadcrumb & Page Headings Management Routes
        Route::prefix('/breadcrumb')
          ->middleware(['permission:Breadcrumbs'])
          ->group(function () {
            // Breadcrumb Image Management
            Route::get('', 'Admin\BasicSettings\PageHeadingController@breadcrumb')->name('admin.breadcrumb.image');
            Route::post('/breadcrumb/update', 'Admin\BasicSettings\PageHeadingController@updateBreadcrumb')->name('admin.breadcrumb.update');

            // Breadcrumb Headings Management 
            Route::prefix('/headings')->group(function () {
              Route::get('', 'Admin\BasicSettings\PageHeadingController@pageHeadings')->name('admin.basic_settings.page_headings');
              Route::post('/update', 'Admin\BasicSettings\PageHeadingController@updatePageHeadings')->name('admin.basic_settings.update_page_headings');
            });
          });

        // FAQ Management Routes start
        Route::prefix('/faq-management')
          ->middleware(['permission:FAQs'])
          ->group(function () {
            Route::get('', 'Admin\FaqController@index')->name('admin.faq_management');
            Route::post('/store', 'Admin\FaqController@store')->name('admin.faq_management.store_faq');
            Route::post('/update', 'Admin\FaqController@update')->name('admin.faq_management.update_faq');
            Route::post('/delete/{id}', 'Admin\FaqController@destroy')->name('admin.faq_management.delete_faq');
            Route::post('/bulk-delete', 'Admin\FaqController@bulkDestroy')->name('admin.faq_management.bulk_delete_faq');
          });
        // FAQ Management Routes end

        // Blog Management Routes start
        Route::prefix('/blog-management')
          ->middleware(['permission:Blogs'])
          ->group(function () {

            Route::prefix('/categories')->group(function () {
              Route::get('', 'Admin\Blog\CategoryController@index')->name('admin.blog_management.categories');
              Route::post('/store', 'Admin\Blog\CategoryController@store')->name('admin.blog_management.store_category');
              Route::post('/update', 'Admin\Blog\CategoryController@update')->name('admin.blog_management.update_category');
              Route::post('/delete/{id}', 'Admin\Blog\CategoryController@destroy')->name('admin.blog_management.delete_category');
              Route::post('/bulk-delete', 'Admin\Blog\CategoryController@bulkDestroy')->name('admin.blog_management.bulk_delete_category');
            });

            Route::prefix('/posts')->group(function () {
              Route::get('', 'Admin\Blog\PostController@index')->name('admin.blog_management.posts');
              Route::get('/create', 'Admin\Blog\PostController@create')->name('admin.blog_management.create_post');
              Route::post('/store', 'Admin\Blog\PostController@store')->name('admin.blog_management.store_post');
              Route::get('/edit/{id}', 'Admin\Blog\PostController@edit')->name('admin.blog_management.edit_post');
              Route::post('/update/{id}', 'Admin\Blog\PostController@update')->name('admin.blog_management.update_post');
              Route::post('/delete/{id}', 'Admin\Blog\PostController@destroy')->name('admin.blog_management.delete_post');
              Route::post('/bulk-delete', 'Admin\Blog\PostController@bulkDestroy')->name('admin.blog_management.bulk_delete_post');
            });
          });
        // Blog Management Routes end

        // Contact Section Management routes start
        Route::prefix('/contact')
          ->middleware(['permission:Contact Page'])
          ->group(function () {
            Route::get('', 'Admin\HomePage\ContactController@index')->name('admin.home_page.contact.index');
            Route::post('/update-info', 'Admin\HomePage\ContactController@updateInfo')->name('admin.home_page.contact.update_info');
            Route::post('/update-content', 'Admin\HomePage\ContactController@updateContentInfo')->name('admin.home_page.contact.update_content_info');
          });
        // Contact Section Management routes end

        // Additional Pages (Custom Pages) Management routes start
        Route::prefix('/additional-pages')
          ->middleware(['permission:Additional Pages'])
          ->group(function () {
            Route::get('', 'Admin\CustomPageController@index')->name('admin.custom_pages');
            Route::get('/create', 'Admin\CustomPageController@create')->name('admin.custom_pages.create_page');
            Route::post('/store', 'Admin\CustomPageController@store')->name('admin.custom_pages.store_page');
            Route::get('/edit/{id}', 'Admin\CustomPageController@edit')->name('admin.custom_pages.edit_page');
            Route::post('/update/{id}', 'Admin\CustomPageController@update')->name('admin.custom_pages.update_page');
            Route::post('/delete/{id}', 'Admin\CustomPageController@destroy')->name('admin.custom_pages.delete_page');
            Route::post('/bulk-delete', 'Admin\CustomPageController@bulkDestroy')->name('admin.custom_pages.bulk_delete_page');
          });
        // Additional Pages (Custom Pages) Management routes end

        // SEO information Routes start
        Route::prefix('/seo-information')
          ->middleware(['permission:SEO Information'])
          ->group(function () {
            Route::get('', 'Admin\BasicSettings\SEOController@index')->name('admin.basic_settings.seo');
            Route::post('/update', 'Admin\BasicSettings\SEOController@update')->name('admin.basic_settings.update_seo');
          });
        // SEO information Routes end
      });

    // Shop Management Routes start
    Route::prefix('/shop-management')
      ->middleware(['permission:Shop Management,Shop Settings,Tax Amounts,Shipping Charges,Shop Coupons,Manage Products,Orders,Shop Report'])
      ->group(function () {
        // Shop Settings route start here
        Route::prefix('/settings')
          ->middleware(['permission:Shop Settings'])
          ->group(function () {
            Route::get('', 'Admin\BasicSettings\BasicController@settings')->name('admin.shop_management.settings');
            Route::post('/update', 'Admin\BasicSettings\BasicController@updateSettings')->name('admin.shop_management.update_settings');
          });

        // Shop Tax route start here
        Route::prefix('/tax')
          ->middleware(['permission:Tax Amounts'])
          ->group(function () {
            Route::get('/', 'Admin\BasicSettings\BasicController@productTaxAmount')->name('admin.shop_management.tax_amount');
            Route::post('/update', 'Admin\BasicSettings\BasicController@updateProductTaxAmount')->name('admin.shop_management.update_tax_amount');
          });

        // Shipping Charges Management
        Route::prefix('/shipping-charges')
          ->middleware(['permission:Shipping Charges'])
          ->group(function () {
            Route::get('', 'Admin\Shop\ShippingChargeController@index')->name('admin.shop_management.shipping_charges');
            Route::post('/store', 'Admin\Shop\ShippingChargeController@store')->name('admin.shop_management.store_charge');
            Route::post('/update', 'Admin\Shop\ShippingChargeController@update')->name('admin.shop_management.update_charge');
            Route::post('/delete/{id}', 'Admin\Shop\ShippingChargeController@destroy')->name('admin.shop_management.delete_charge');
          });

        // Coupon Management
        Route::prefix('/coupons')
          ->middleware(['permission:Shop Coupons'])
          ->group(function () {
            Route::get('', 'Admin\Shop\CouponController@index')->name('admin.shop_management.coupons');
            Route::post('/store', 'Admin\Shop\CouponController@store')->name('admin.shop_management.store_coupon');
            Route::post('/update', 'Admin\Shop\CouponController@update')->name('admin.shop_management.update_coupon');
            Route::post('/delete/{id}', 'Admin\Shop\CouponController@destroy')->name('admin.shop_management.delete_coupon');
          });

        // Product Category Management
        Route::prefix('/products/categories')
          ->middleware(['permission:Manage Products'])
          ->group(function () {
            Route::get('', 'Admin\Shop\CategoryController@index')->name('admin.shop_management.product.categories');
            Route::post('/store', 'Admin\Shop\CategoryController@store')->name('admin.shop_management.product.store_category');
            Route::post('/update', 'Admin\Shop\CategoryController@update')->name('admin.shop_management.product.update_category');
            Route::post('/delete/{id}', 'Admin\Shop\CategoryController@destroy')->name('admin.shop_management.product.delete_category');
            Route::post('/bulk-delete', 'Admin\Shop\CategoryController@bulkDestroy')->name('admin.shop_management.product.bulk_delete_category');
          });

        // Product Management
        Route::prefix('/products')
          ->middleware(['permission:Manage Products'])
          ->group(function () {
            Route::get('', 'Admin\Shop\ProductController@index')->name('admin.shop_management.products');
            Route::get('/select-type', 'Admin\Shop\ProductController@productType')->name('admin.shop_management.select_product_type');
            Route::get('/create/{type}', 'Admin\Shop\ProductController@create')->name('admin.shop_management.create_product');
            Route::post('/upload-slider-image', 'Admin\Shop\ProductController@uploadImage')->name('admin.shop_management.upload_slider_image');
            Route::post('/remove-slider-image', 'Admin\Shop\ProductController@removeImage')->name('admin.shop_management.remove_slider_image');
            Route::post('/store', 'Admin\Shop\ProductController@store')->name('admin.shop_management.store_product');
            Route::post('/{id}/update-featured-status', 'Admin\Shop\ProductController@updateFeaturedStatus')->name('admin.shop_management.product.update_featured_status');
            Route::get('/edit/{id}/{type}', 'Admin\Shop\ProductController@edit')->name('admin.shop_management.edit_product');
            Route::post('/detach-slider-image', 'Admin\Shop\ProductController@detachImage')->name('admin.shop_management.detach_slider_image');
            Route::post('/update/{id}', 'Admin\Shop\ProductController@update')->name('admin.shop_management.update_product');
            Route::post('/delete/{id}', 'Admin\Shop\ProductController@destroy')->name('admin.shop_management.delete_product');
            Route::post('/bulk-delete', 'Admin\Shop\ProductController@bulkDestroy')->name('admin.shop_management.bulk_delete_product');
          });

        // Order Management
        Route::prefix('/orders')
          ->middleware(['permission:Orders'])
          ->group(function () {
            Route::get('', 'Admin\Shop\OrderController@orders')->name('admin.shop_management.orders');
            Route::post('/bulk-delete', 'Admin\Shop\OrderController@bulkDestroy')->name('admin.shop_management.bulk_delete_order');
            Route::get('/{id}/details', 'Admin\Shop\OrderController@show')->name('admin.shop_management.order.details');
            Route::post('/{id}/update-payment-status', 'Admin\Shop\OrderController@updatePaymentStatus')->name('admin.shop_management.order.update_payment_status');
            Route::post('/{id}/update-order-status', 'Admin\Shop\OrderController@updateOrderStatus')->name('admin.shop_management.order.update_order_status');
            Route::post('/{id}/delete', 'Admin\Shop\OrderController@destroy')->name('admin.shop_management.order.delete');
          });

        // Report Management
        Route::prefix('/reports')
          ->middleware(['permission:Shop Report'])
          ->group(function () {
            Route::get('', 'Admin\Shop\OrderController@report')->name('admin.shop_management.report');
            Route::get('/export', 'Admin\Shop\OrderController@exportReport')->name('admin.shop_management.export_report');
          });
      });
    // Shop Management Routes end

    Route::get('/transaction', 'Admin\AdminController@transaction')->name('admin.dashboard.transaction')->middleware('permission:Transactions');

    // Support Tickets Management Routes start
    Route::prefix('/support-tickets')->middleware('permission:Support Tickets')->group(function () {

      // Ticket Operations
      Route::get('', 'Admin\SupportTicketController@tickets')->name('admin.support_tickets');
      Route::post('/bulk-delete', 'Admin\SupportTicketController@bulkDestroy')->name('admin.support_tickets.bulk_delete');
      Route::post('/store-temp-file', 'Admin\SupportTicketController@storeTempFile')->name('admin.support_tickets.store_temp_file');

      // Individual Ticket Actions
      Route::prefix('/ticket/{id}')->group(function () {
        Route::post('/assign-admin', 'Admin\SupportTicketController@assignAdmin')->name('admin.support_ticket.assign_admin');
        Route::get('/unassign-stuff', 'Admin\SupportTicketController@unassign_stuff')->name('admin.support_tickets.unassign');
        Route::get('/conversation', 'Admin\SupportTicketController@conversation')->name('admin.support_ticket.conversation');
        Route::post('/close', 'Admin\SupportTicketController@close')->name('admin.support_ticket.close');
        Route::post('/reply', 'Admin\SupportTicketController@reply')->name('admin.support_ticket.reply');
        Route::post('/delete', 'Admin\SupportTicketController@destroy')->name('admin.support_ticket.delete');
      });
    });
    // Support Tickets Management Routes end

    // Advertisement Management routes start
    Route::prefix('/advertisements')->middleware('permission:Advertisements')->group(function () {

      Route::prefix('/settings')->group(function () {
        Route::get('', 'Admin\AdvertisementController@advertiseSettings')->name('admin.advertise.settings');
        Route::post('/update', 'Admin\AdvertisementController@updateAdvertiseSettings')->name('admin.advertise.update_settings');
      });

      // Advertisement CRUD Operations
      Route::get('', 'Admin\AdvertisementController@index')->name('admin.advertise.all_advertisement');
      Route::post('/store', 'Admin\AdvertisementController@store')->name('admin.advertise.store_advertisement');
      Route::post('/update', 'Admin\AdvertisementController@update')->name('admin.advertise.update_advertisement');
      Route::post('/delete/{id}', 'Admin\AdvertisementController@destroy')->name('admin.advertise.delete_advertisement');
      Route::post('/bulk-delete', 'Admin\AdvertisementController@bulkDestroy')->name('admin.advertise.bulk_delete_advertisement');
    });
    // Advertisement Management routes end

    // Announcement Popups Management routes start
    Route::prefix('/announcements/popups')->middleware('permission:Announcement Popups')->group(function () {
      // Popup List and Creation Workflow
      Route::get('', 'Admin\PopupController@index')->name('admin.announcement_popups');
      Route::get('/select-type', 'Admin\PopupController@popupType')->name('admin.announcement_popups.select_popup_type');
      Route::get('/create/{type}', 'Admin\PopupController@create')->name('admin.announcement_popups.create_popup');

      // Popup CRUD Operations
      Route::post('/store', 'Admin\PopupController@store')->name('admin.announcement_popups.store_popup');
      Route::post('/{id}/update-status', 'Admin\PopupController@updateStatus')->name('admin.announcement_popups.update_popup_status');
      Route::get('/{id}/edit', 'Admin\PopupController@edit')->name('admin.announcement_popups.edit_popup');
      Route::post('/{id}/update', 'Admin\PopupController@update')->name('admin.announcement_popups.update_popup');
      Route::post('/{id}/delete', 'Admin\PopupController@destroy')->name('admin.announcement_popups.delete_popup');
      Route::post('/bulk-delete', 'Admin\PopupController@bulkDestroy')->name('admin.announcement_popups.bulk_delete_popup');
    });
    // Announcement Popups Management routes start

    // Basic Settings Management Routes
    Route::prefix('/basic-settings')
      ->middleware(['permission:Settings,General Settings,Email Settings,Payment Gateways,Language Management,Plugins,Maintenance Modes,Cookie Alerts,Social Media'])
      ->group(function () {

        // General Settings
        Route::prefix('/general-setting')
          ->middleware(['permission:General Settings'])
          ->group(function () {
            Route::get('', 'Admin\BasicSettings\BasicController@generalSettings')
              ->name('admin.basic_settings.general_settings');
            Route::post('/update', 'Admin\BasicSettings\BasicController@updateGeneralSetting')
              ->name('admin.basic_settings.general_settings.update');
          });

        // Email Configuration
        Route::prefix('/email')
          ->middleware(['permission:Email Settings'])
          ->group(function () {
            // Admin Email Settings
            Route::prefix('/admin')->group(function () {
              Route::get('/from', 'Admin\BasicSettings\BasicController@mailFromAdmin')
                ->name('admin.basic_settings.mail_from_admin');
              Route::post('/from', 'Admin\BasicSettings\BasicController@updateMailFromAdmin')
                ->name('admin.basic_settings.update_mail_from_admin');
              Route::get('/to', 'Admin\BasicSettings\BasicController@mailToAdmin')
                ->name('admin.basic_settings.mail_to_admin');
              Route::post('/to', 'Admin\BasicSettings\BasicController@updateMailToAdmin')
                ->name('admin.basic_settings.update_mail_to_admin');
            });

            // Email Templates
            Route::prefix('/templates')
              ->middleware(['permission:Email Settings'])
              ->group(function () {
                Route::get('', 'Admin\BasicSettings\MailTemplateController@index')
                  ->name('admin.basic_settings.mail_templates');
                Route::get('/{id}/edit', 'Admin\BasicSettings\MailTemplateController@edit')
                  ->name('admin.basic_settings.edit_mail_template');
                Route::post('/{id}', 'Admin\BasicSettings\MailTemplateController@update')
                  ->name('admin.basic_settings.update_mail_template');
              });
          });

        // Plugins & Integrations
        Route::prefix('/plugins')
          ->middleware(['permission:Plugins'])
          ->group(function () {
            Route::get('', 'Admin\BasicSettings\BasicController@plugins')
              ->name('admin.basic_settings.plugins');

            Route::post('/recaptcha', 'Admin\BasicSettings\BasicController@updateRecaptcha')
              ->name('admin.basic_settings.update_recaptcha');
            Route::post('/disqus', 'Admin\BasicSettings\BasicController@updateDisqus')
              ->name('admin.basic_settings.update_disqus');
            Route::post('/google-map', 'Admin\BasicSettings\BasicController@updateGoogleMap')
              ->name('admin.basic_settings.update_google_map_api_key');
            Route::post('/whatsapp', 'Admin\BasicSettings\BasicController@updateWhatsApp')
              ->name('admin.basic_settings.update_whatsapp');
            Route::post('/facebook', 'Admin\BasicSettings\BasicController@updateFacebook')
              ->name('admin.basic_settings.update_facebook');
            Route::post('/google', 'Admin\BasicSettings\BasicController@updateGoogle')
              ->name('admin.basic_settings.update_google');
            Route::post('/pusher', 'Admin\BasicSettings\BasicController@updatePusher')
              ->name('admin.basic_settings.update_pusher');
          });

        // System Operations
        Route::prefix('/system')
          ->middleware(['permission:Maintenance Modes'])
          ->group(function () {
            // Maintenance Mode
            Route::get('/maintenance', 'Admin\BasicSettings\BasicController@maintenance')
              ->name('admin.basic_settings.maintenance_mode');
            Route::post('/maintenance', 'Admin\BasicSettings\BasicController@updateMaintenance')
              ->name('admin.basic_settings.update_maintenance_mode');
          });

        // Cookie Alert
        Route::prefix('/cookie-alert')
          ->middleware(['permission:Cookie Alerts'])
          ->group(function () {
            Route::get('', 'Admin\BasicSettings\CookieAlertController@cookieAlert')
              ->name('admin.basic_settings.cookie_alert');
            Route::post('/update', 'Admin\BasicSettings\CookieAlertController@updateCookieAlert')
              ->name('admin.basic_settings.update_cookie_alert');
          });

        // Social Media Management
        Route::prefix('/social-media')
          ->middleware(['permission:Social Media'])
          ->group(function () {
            Route::get('', 'Admin\BasicSettings\SocialMediaController@index')
              ->name('admin.basic_settings.social_medias');
            Route::post('/store', 'Admin\BasicSettings\SocialMediaController@store')
              ->name('admin.basic_settings.store_social_media');
            Route::post('/update', 'Admin\BasicSettings\SocialMediaController@update')
              ->name('admin.basic_settings.update_social_media');
            Route::post('/{id}', 'Admin\BasicSettings\SocialMediaController@destroy')
              ->name('admin.basic_settings.delete_social_media');
          });
      });

    // Payment Gateways Management Routes
    Route::prefix('/payment-gateways')
      ->middleware(['permission:Settings,Payment Gateways'])
      ->group(function () {

        // Online Payment Gateways
        Route::prefix('/online')->group(function () {
          Route::get('', 'Admin\PaymentGateway\OnlineGatewayController@index')
            ->name('admin.payment_gateways.online_gateways');

          // Gateway-specific update routes
          Route::post('/freshpay', 'Admin\PaymentGateway\OnlineGatewayController@updateFreshpayInfo')
            ->name('admin.payment_gateways.update_freshpay_info');
          Route::post('/phonepe', 'Admin\PaymentGateway\OnlineGatewayController@updatePhonepeInfo')
            ->name('admin.payment_gateways.update_phonepe_info');
          Route::post('/toyyibpay', 'Admin\PaymentGateway\OnlineGatewayController@updateToyyibpayInfo')
            ->name('admin.payment_gateways.update_toyyibpay_info');
          Route::post('/xendit', 'Admin\PaymentGateway\OnlineGatewayController@updateXenditInfo')
            ->name('admin.payment_gateways.update_xendit_info');
          Route::post('/yoco', 'Admin\PaymentGateway\OnlineGatewayController@updateYocoInfo')
            ->name('admin.payment_gateways.update_yoco_info');
          Route::post('/paypal', 'Admin\PaymentGateway\OnlineGatewayController@updatePayPalInfo')
            ->name('admin.payment_gateways.update_paypal_info');
          Route::post('/instamojo', 'Admin\PaymentGateway\OnlineGatewayController@updateInstamojoInfo')
            ->name('admin.payment_gateways.update_instamojo_info');
          Route::post('/paystack', 'Admin\PaymentGateway\OnlineGatewayController@updatePaystackInfo')
            ->name('admin.payment_gateways.update_paystack_info');
          Route::post('/flutterwave', 'Admin\PaymentGateway\OnlineGatewayController@updateFlutterwaveInfo')
            ->name('admin.payment_gateways.update_flutterwave_info');
          Route::post('/razorpay', 'Admin\PaymentGateway\OnlineGatewayController@updateRazorpayInfo')
            ->name('admin.payment_gateways.update_razorpay_info');
          Route::post('/mercadopago', 'Admin\PaymentGateway\OnlineGatewayController@updateMercadoPagoInfo')
            ->name('admin.payment_gateways.update_mercadopago_info');
          Route::post('/mollie', 'Admin\PaymentGateway\OnlineGatewayController@updateMollieInfo')
            ->name('admin.payment_gateways.update_mollie_info');
          Route::post('/stripe', 'Admin\PaymentGateway\OnlineGatewayController@updateStripeInfo')
            ->name('admin.payment_gateways.update_stripe_info');
          Route::post('/paytm', 'Admin\PaymentGateway\OnlineGatewayController@updatePaytmInfo')
            ->name('admin.payment_gateways.update_paytm_info');
          Route::post('/authorizenet', 'Admin\PaymentGateway\OnlineGatewayController@updateAuthorizeNetInfo')
            ->name('admin.payment_gateways.update_authorizenet_info');
          Route::post('/iyzico', 'Admin\PaymentGateway\OnlineGatewayController@updateIyzicoInfo')
            ->name('admin.payment_gateways.update_iyzico_info');
          Route::post('/midtrans', 'Admin\PaymentGateway\OnlineGatewayController@updateMidtransInfo')
            ->name('admin.payment_gateways.update_midtrans_info');
          Route::post('/myfatoorah', 'Admin\PaymentGateway\OnlineGatewayController@updateMyFatoorahInfo')
            ->name('admin.payment_gateways.update_myfatoorah_info');
          Route::post('/perfect-money', 'Admin\PaymentGateway\OnlineGatewayController@updatePerfectMoneyInfo')
            ->name('admin.payment_gateways.update_perfect_money_info');
          Route::post('/paytabs', 'Admin\PaymentGateway\OnlineGatewayController@updatePaytabsInfo')
            ->name('admin.payment_gateways.update_paytabs_info');
        });

        // Offline Payment Gateways
        Route::prefix('/offline')->group(function () {
          Route::get('', 'Admin\PaymentGateway\OfflineGatewayController@index')
            ->name('admin.payment_gateways.offline_gateways');
          Route::post('/store', 'Admin\PaymentGateway\OfflineGatewayController@store')
            ->name('admin.payment_gateways.store_offline_gateway');
          Route::post('/update', 'Admin\PaymentGateway\OfflineGatewayController@update')
            ->name('admin.payment_gateways.update_offline_gateway');
          Route::post('/{id}/status', 'Admin\PaymentGateway\OfflineGatewayController@updateStatus')
            ->name('admin.payment_gateways.update_status');
          Route::post('/{id}', 'Admin\PaymentGateway\OfflineGatewayController@destroy')
            ->name('admin.payment_gateways.delete_offline_gateway');
        });
      });

    // Language Management Routes start
    Route::prefix('/language-management')
      ->middleware(['permission:Settings,Language Management'])
      ->group(function () {
        // Language Settings
        Route::prefix('/settings')->group(function () {
          Route::get('', 'Admin\LanguageController@settings')
            ->name('admin.language_management.settings');
          Route::post('/update', 'Admin\LanguageController@settingsUpdate')
            ->name('admin.language_management.settings.update');
        });

        // Language CRUD Operations
        Route::get('', 'Admin\LanguageController@index')
          ->name('admin.language_management');
        Route::post('/store', 'Admin\LanguageController@store')
          ->name('admin.language_management.store');
        Route::post('/update', 'Admin\LanguageController@update')
          ->name('admin.language_management.update');
        Route::post('/{id}/default', 'Admin\LanguageController@makeDefault')
          ->name('admin.language_management.make_default_language');
        Route::post('/{id}', 'Admin\LanguageController@destroy')
          ->name('admin.language_management.delete');

        // Frontend Keywords Management
        Route::prefix('/keywords')->group(function () {
          Route::post('/store', 'Admin\LanguageController@addKeyword')
            ->name('admin.language_management.add_keyword');
          Route::get('/{id}/edit', 'Admin\LanguageController@editKeyword')
            ->name('admin.language_management.edit_keyword');
          Route::post('/{id}', 'Admin\LanguageController@updateKeyword')
            ->name('admin.language_management.update_keyword');
        });

        // Admin Panel Keywords Management
        Route::prefix('/admin-keywords')->group(function () {
          Route::post('/store', 'Admin\LanguageController@addAdminKeyword')
            ->name('admin.language_management.admin.add_keyword');
          Route::get('/{id}/edit', 'Admin\LanguageController@editAdminKeyword')
            ->name('admin.language_management.admin.edit_keyword');
          Route::post('/{id}', 'Admin\LanguageController@updateAdminKeyword')
            ->name('admin.language_management.admin.update_keyword');
        });
      });
    // Language Management Routes end

    // Staff Management Routes start
    Route::prefix('/staff-management')
      ->middleware('permission:Staffs Management')
      ->group(function () {

        // Role & Permission Management
        Route::prefix('/roles')->group(function () {
          // Role CRUD Operations
          Route::get('', 'Admin\Administrator\RolePermissionController@index')
            ->name('admin.admin_management.role_permissions');
          Route::post('/store', 'Admin\Administrator\RolePermissionController@store')
            ->name('admin.admin_management.store_role');
          Route::post('/update', 'Admin\Administrator\RolePermissionController@update')
            ->name('admin.admin_management.update_role');
          Route::post('delete/{id}', 'Admin\Administrator\RolePermissionController@destroy')
            ->name('admin.admin_management.delete_role');

          // Role Permissions Management
          Route::prefix('/{id}/permissions')->group(function () {
            Route::get('', 'Admin\Administrator\RolePermissionController@permissions')
              ->name('admin.admin_management.role.permissions');
            Route::post('/update-permissions', 'Admin\Administrator\RolePermissionController@updatePermissions')
              ->name('admin.admin_management.role.update_permissions');
          });
        });

        // Admin User Management
        Route::prefix('/staff')->group(function () {
          // Admin CRUD Operations
          Route::get('', 'Admin\Administrator\SiteAdminController@index')
            ->name('admin.admin_management.registered_admins');
          Route::post('/store', 'Admin\Administrator\SiteAdminController@store')
            ->name('admin.admin_management.store_admin');
          Route::post('/update', 'Admin\Administrator\SiteAdminController@update')
            ->name('admin.admin_management.update_admin');
          Route::post('/delete/{id}', 'Admin\Administrator\SiteAdminController@destroy')
            ->name('admin.admin_management.delete_admin');

          // Admin Status Management
          Route::post('/{id}/status', 'Admin\Administrator\SiteAdminController@updateStatus')
            ->name('admin.admin_management.update_status');
        });
      });
    // Staff Management Routes end

    // upload image in summernote route
    Route::prefix('/summernote')->group(function () {
      Route::post('/upload-image', 'Admin\SummernoteController@upload');
      Route::post('/remove-image', 'Admin\SummernoteController@remove');
    });
  // Utility Routes
  Route::get('/language-management/{id}/check-rtl', 'Admin\LanguageController@checkRTL');
  Route::get('/get-subcategory', 'Admin\space\SpaceController@getSpaceSubcategories')
    ->name('admin.space_management.space.get_subcategory');
  Route::get('/select-space-type', 'Admin\space\SpaceController@spaceType')
    ->name('admin.space_management.space.select_space_type')->middleware('adminLang');

  Route::get('/countries', 'Admin\BasicSettings\BasicController@getCountries')->name('admin.get_countries');
  Route::get('/states', 'Admin\BasicSettings\BasicController@getStates')->name('admin.get_states');
  Route::get('/cities', 'Admin\BasicSettings\BasicController@getCities')->name('admin.get_cities');
  });




