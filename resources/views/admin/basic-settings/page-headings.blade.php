@extends('admin.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('admin.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Headings') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard', ['language' => $defaultLang->code]) }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Pages') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Breadcrumb') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Headings') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form
                    action="{{ route('admin.basic_settings.update_page_headings', ['language' => request()->input('language')]) }}"
                    method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-title">{{ __('Update Page Headings') }}</div>
                            </div>

                            <div class="col-lg-2">

                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-10 mx-auto">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Vendors Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="vendor_page_title"
                                                value="{{ !is_null($data) ? $data->vendor_page_title : '' }}">
                                            @error('vendor_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Checkout Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="checkout_page_title"
                                                value="{{ !is_null($data) ? $data->checkout_page_title : '' }}">
                                            @error('checkout_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Pricing Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="pricing_page_title"
                                                value="{{ !is_null($data) ? $data->pricing_page_title : '' }}">
                                            @error('pricing_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('About Us Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="about_us_page_title"
                                                value="{{ !is_null($data) ? $data->about_us_page_title : '' }}">
                                            @error('about_us_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Blog Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="blog_page_title"
                                                value="{{ !is_null($data) ? $data->blog_page_title : '' }}">
                                            @error('blog_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Post Details Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="post_details_page_title"
                                                value="{{ !is_null($data) ? $data->post_details_page_title : '' }}">
                                            @error('post_details_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Contact Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="contact_page_title"
                                                value="{{ !is_null($data) ? $data->contact_page_title : '' }}">
                                            @error('contact_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Error Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="error_page_title"
                                                value="{{ !is_null($data) ? $data->error_page_title : '' }}">
                                            @error('error_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('FAQ Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="faq_page_title"
                                                value="{{ !is_null($data) ? $data->faq_page_title : '' }}">
                                            @error('faq_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Customer Forget Password Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="forget_password_page_title"
                                                value="{{ !is_null($data) ? $data->forget_password_page_title : '' }}">
                                            @error('forget_password_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Customer Login Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="login_page_title"
                                                value="{{ !is_null($data) ? $data->login_page_title : '' }}">
                                            @error('login_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Customer Signup Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="signup_page_title"
                                                value="{{ !is_null($data) ? $data->signup_page_title : '' }}">
                                            @error('signup_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Spaces Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="spaces_page_title"
                                                value="{{ !is_null($data) ? $data->spaces_page_title : '' }}">
                                            @error('spaces_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Space Details Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="space_details_page_title"
                                                value="{{ !is_null($data) ? $data->space_details_page_title : '' }}">
                                            @error('space_details_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Vendor Login Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="seller_login_page_title"
                                                value="{{ !is_null($data) ? $data->seller_login_page_title : '' }}">
                                            @error('seller_login_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Vendor Signup Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="seller_signup_page_title"
                                                value="{{ !is_null($data) ? $data->seller_signup_page_title : '' }}">
                                            @error('seller_signup_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Vendor Forgot Password Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="seller_forget_password_page_title"
                                                value="{{ !is_null($data) ? $data->seller_forget_password_page_title : '' }}">
                                            @error('seller_forget_password_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Customer Dashboard Page Title') . '*' }}</label>
                                            <input type="text" class="form-control"
                                                name="customer_dashboard_page_title"
                                                value="{{ !is_null($data) ? $data->customer_dashboard_page_title : '' }}">
                                            @error('customer_dashboard_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Customer Booking Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="customer_booking_page_title"
                                                value="{{ !is_null($data) ? $data->customer_booking_page_title : '' }}">
                                            @error('customer_booking_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Customer Booking Details Page Title') . '*' }}</label>
                                            <input type="text" class="form-control"
                                                name="customer_booking_details_page_title"
                                                value="{{ !is_null($data) ? $data->customer_booking_details_page_title : '' }}">
                                            @error('customer_booking_details_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Customer Order Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="customer_order_page_title"
                                                value="{{ !is_null($data) ? $data->customer_order_page_title : '' }}">
                                            @error('customer_order_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Customer Order Details Page Title') . '*' }}</label>
                                            <input type="text" class="form-control"
                                                name="customer_order_details_page_title"
                                                value="{{ !is_null($data) ? $data->customer_order_details_page_title : '' }}">
                                            @error('customer_order_details_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Customer Wishlist Page Title') . '*' }}</label>
                                            <input type="text" class="form-control"
                                                name="customer_wishlist_page_title"
                                                value="{{ !is_null($data) ? $data->customer_wishlist_page_title : '' }}">
                                            @error('customer_wishlist_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Customer Edit Profile Page Title') . '*' }}</label>
                                            <input type="text" class="form-control"
                                                name="customer_edit_profile_page_title"
                                                value="{{ !is_null($data) ? $data->customer_edit_profile_page_title : '' }}">
                                            @error('customer_edit_profile_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Customer Change Password Page Title') . '*' }}</label>
                                            <input type="text" class="form-control"
                                                name="customer_change_password_page_title"
                                                value="{{ !is_null($data) ? $data->customer_change_password_page_title : '' }}">
                                            @error('customer_change_password_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Shop Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="shop_page_title"
                                                value="{{ !is_null($data) ? $data->shop_page_title : '' }}">
                                            @error('shop_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Cart Page Title') . '*' }}</label>
                                            <input type="text" class="form-control" name="cart_page_title"
                                                value="{{ !is_null($data) ? $data->cart_page_title : '' }}">
                                            @error('cart_page_title')
                                                <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
