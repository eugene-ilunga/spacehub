@extends('admin.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('admin.partials.rtl-style')

@section('content')
<div class="page-header">
    <h4 class="page-title">{{ __('SEO Information') }}</h4>
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
            <a href="#">{{ __('SEO Information') }}</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <form action="{{ route('admin.basic_settings.update_seo', ['language' => request()->input('language')]) }}"
                method="post">
                @csrf
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card-title">{{ __('Update SEO Information') }}</div>
                        </div>

                        <div class="col-lg-2"> </div>
                       
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Meta Keywords For Home Page') }}</label>
                                <input class="form-control" name="meta_keyword_home"
                                    value="{{ is_null($data) ? '' : $data->meta_keyword_home }}"
                                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                            </div>

                            <div class="form-group">
                                <label>{{ __('Meta Description For Home Page') }}</label>
                                <textarea class="form-control" name="meta_description_home" rows="5"
                                    placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->meta_description_home }}</textarea>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Meta Keywords For Spaces Page') }}</label>
                                <input class="form-control" name="meta_keyword_spaces"
                                    value="{{ is_null($data) ? '' : $data->meta_keyword_spaces }}"
                                    placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                            </div>

                            <div class="form-group">
                                <label>{{ __('Meta Description For Spaces Page') }}</label>
                                <textarea class="form-control" name="meta_description_spaces" rows="5"
                                    placeholder="{{{__('Enter Meta Description')}}}">{{ is_null($data) ? '' : $data->meta_description_spaces }}</textarea>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Meta Keywords For Space Booking Page') }}</label>
                                <input class="form-control" name="meta_keyword_space_booking"
                                    value="{{ is_null($data) ? '' : $data->meta_keyword_space_booking }}"
                                    placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                            </div>

                            <div class="form-group">
                                <label>{{ __('Meta Description For Space Booking Page') }}</label>
                                <textarea class="form-control" name="meta_description_space_booking" rows="5"
                                    placeholder="{{{__('Enter Meta Description')}}}">{{ is_null($data) ? '' : $data->meta_description_space_booking }}</textarea>
                            </div>
                        </div>
                                                    <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For Vendor Forget Password Page') }}</label>
                                    <input class="form-control" name="meta_keyword_vendor_forget_password"
                                        value="{{ is_null($data) ? '' : $data->meta_keyword_vendor_forget_password }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For Vendor Forget Password Page') }}</label>
                                    <textarea class="form-control" name="meta_description_vendor_forget_password"
                                        rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->meta_description_vendor_forget_password }}</textarea>
                                </div>
                            </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For Pricing Page') }}</label>
                                    <input class="form-control" name="meta_keyword_pricing"
                                        value="{{ is_null($data) ? '' : $data->meta_keyword_pricing }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For Pricing Page') }}</label>
                                    <textarea class="form-control" name="meta_description_pricing" rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->meta_description_pricing }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For Vendor Page') }}</label>
                                    <input class="form-control" name="vendor_page_meta_keywords"
                                        value="{{ is_null($data) ? '' : $data->vendor_page_meta_keywords }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For Vendor Page') }}</label>
                                    <textarea class="form-control" name="vendor_page_meta_description" rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->vendor_page_meta_description }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For Vendor Details Page') }}</label>
                                    <input class="form-control" name="vendor_details_page_meta_keywords"
                                        value="{{ is_null($data) ? '' : $data->vendor_details_page_meta_keywords }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For Vendor Details Page') }}</label>
                                    <textarea class="form-control" name="vendor_details_page_meta_description" rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->vendor_details_page_meta_description }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For Product Page') }}</label>
                                    <input class="form-control" name="shop_page_meta_keywords"
                                        value="{{ is_null($data) ? '' : $data->shop_page_meta_keywords }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For Product Page') }}</label>
                                    <textarea class="form-control" name="shop_page_meta_description" rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->shop_page_meta_description }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For Cart Page') }}</label>
                                    <input class="form-control" name="cart_page_meta_keywords"
                                        value="{{ is_null($data) ? '' : $data->cart_page_meta_keywords }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For Cart Page') }}</label>
                                    <textarea class="form-control" name="cart_page_meta_description" rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->cart_page_meta_description }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For Shop Checkout Page') }}</label>
                                    <input class="form-control" name="shop_checkout_page_meta_keywords"
                                        value="{{ is_null($data) ? '' : $data->shop_checkout_page_meta_keywords }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For Shop Checkout Page') }}</label>
                                    <textarea class="form-control" name="shop_checkout_page_meta_description" rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->shop_checkout_page_meta_description }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For Blog Page') }}</label>
                                    <input class="form-control" name="meta_keyword_blog"
                                        value="{{ is_null($data) ? '' : $data->meta_keyword_blog }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For Blog Page') }}</label>
                                    <textarea class="form-control" name="meta_description_blog" rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->meta_description_blog }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For About Us Page') }}</label>
                                    <input class="form-control" name="meta_keyword_aboutus"
                                        value="{{ is_null($data) ? '' : $data->meta_keyword_aboutus }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For About Us Page') }}</label>
                                    <textarea class="form-control" name="meta_description_aboutus" rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->meta_description_aboutus }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For FAQ Page') }}</label>
                                    <input class="form-control" name="meta_keyword_faq"
                                        value="{{ is_null($data) ? '' : $data->meta_keyword_faq }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For FAQ Page') }}</label>
                                    <textarea class="form-control" name="meta_description_faq" rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->meta_description_faq }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For Terms and Conditions Page') }}</label>
                                    <input class="form-control" name="meta_keyword_term_and_condition"
                                        value="{{ is_null($data) ? '' : $data->meta_keyword_term_and_condition }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For Terms and Conditions Page Page') }}</label>
                                    <textarea class="form-control" name="meta_description_term_and_condition" rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->meta_description_term_and_condition }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For Contact Page') }}</label>
                                    <input class="form-control" name="meta_keyword_contact"
                                        value="{{ is_null($data) ? '' : $data->meta_keyword_contact }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For Contact Page') }}</label>
                                    <textarea class="form-control" name="meta_description_contact" rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->meta_description_contact }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For Customer Login Page') }}</label>
                                    <input class="form-control" name="meta_keyword_customer_login"
                                        value="{{ is_null($data) ? '' : $data->meta_keyword_customer_login }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For Customer Login Page') }}</label>
                                    <textarea class="form-control" name="meta_description_customer_login" rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->meta_description_customer_login }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For Customer Signup Page') }}</label>
                                    <input class="form-control" name="meta_keyword_customer_signup"
                                        value="{{ is_null($data) ? '' : $data->meta_keyword_customer_signup }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For Customer Signup Page') }}</label>
                                    <textarea class="form-control" name="meta_description_customer_signup" rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->meta_description_customer_signup }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For Customer Forget Password Page') }}</label>
                                    <input class="form-control" name="meta_keyword_customer_forget_password"
                                        value="{{ is_null($data) ? '' : $data->meta_keyword_customer_forget_password }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For Customer Forget Password Page') }}</label>
                                    <textarea class="form-control" name="meta_description_customer_forget_password"
                                        rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->meta_description_customer_forget_password }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For Vendor Login Page') }}</label>
                                    <input class="form-control" name="meta_keyword_vendor_login"
                                        value="{{ is_null($data) ? '' : $data->meta_keyword_vendor_login }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For Vendor Login Page') }}</label>
                                    <textarea class="form-control" name="meta_description_vendor_login" rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->meta_description_vendor_login }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords For Vendor Signup Page') }}</label>
                                    <input class="form-control" name="meta_keyword_vendor_signup"
                                        value="{{ is_null($data) ? '' : $data->meta_keyword_vendor_signup }}"
                                        placeholder="{{__('Enter Meta Keywords')}}" data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Meta Description For Vendor Signup Page') }}</label>
                                    <textarea class="form-control" name="meta_description_vendor_signup" rows="5"
                                        placeholder="{{__('Enter Meta Description')}}">{{ is_null($data) ? '' : $data->meta_description_vendor_signup }}</textarea>
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
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
