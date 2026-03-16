@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Role & Permissions') }}</h4>
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
                <a href="#">{{ __('Staffs Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Role & Permissions') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('admin.admin_management.role.update_permissions', ['id' => $role->id]) }}"
                    method="post">
                    @csrf
                    <div class="card-header">
                        <div class="card-title d-inline-block">{{ __('Permissions of') . ' ' . __($role->name) }}</div>

                        <a class="btn btn-info btn-sm float-right d-inline-block"
                            href="{{ route('admin.admin_management.role_permissions') }}?language={{ $defaultLang->code }}">
                            <span class="btn-label">
                                <i class="fas fa-backward mdb_3242"></i>
                            </span>
                            {{ __('Back') }}
                        </a>
                    </div>

                    <div class="card-body py-5">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="alert alert-warning text-center" role="alert">
                                    <strong
                                        class="text-dark">{{ __('When you choose a parent permission, automatically includes all its child permissions') . '. ' . __('You can also select individual child permissions') . '.' }}</strong>
                                </div>
                            </div>
                        </div>

                        @php $rolePermissions = json_decode($role->permissions); @endphp
                        {{-- @dump($rolePermissions) --}}

                        <div class="row mt-3 justify-content-center">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="selectgroup-wrapper">
                                        <div class="row justify-content-center">
                                            <div class="col-lg-4 col-sm-6">
                                                {{-- space management sub-menu start --}}
                                                <div class="selectgroup-wrapper-item text-center">
                                                    <label class="selectgroup-item d-block">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Space Management" @if (is_array($rolePermissions) && in_array('Space Management', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button parent">{{ __('Space Management') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Space Settings" @if (is_array($rolePermissions) && in_array('Space Settings', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Space Settings') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Holidays" @if (is_array($rolePermissions) && in_array('Holidays', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Holidays') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Coupons" @if (is_array($rolePermissions) && in_array('Coupons', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Coupons') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Specifications" @if (is_array($rolePermissions) && in_array('Specifications', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Specifications') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Featured Management"
                                                            @if (is_array($rolePermissions) && in_array('Featured Management', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Featured Management') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Forms" @if (is_array($rolePermissions) && in_array('Forms', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Forms') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Spaces" @if (is_array($rolePermissions) && in_array('Spaces', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Spaces') }}</span>
                                                    </label>
                                                </div>
                                                {{-- space management sub-menu end --}}
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="selectgroup-wrapper-item text-center">
                                                    {{-- Bookings & Requests sub-menu start --}}
                                                    <label class="selectgroup-item d-block">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Bookings & Requests"
                                                            @if (is_array($rolePermissions) && in_array('Bookings & Requests', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button parent">{{ __('Bookings & Requests') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Quote Requests" @if (is_array($rolePermissions) && in_array('Quote Requests', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Quote Requests') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Tour Requests" @if (is_array($rolePermissions) && in_array('Tour Requests', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Tour Requests') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Booking Management"
                                                            @if (is_array($rolePermissions) && in_array('Booking Management', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Booking Management') }}</span>
                                                    </label>
                                                    {{-- Bookings & Requests sub-menu end --}}
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="selectgroup-wrapper-item text-center">
                                                    <label class="selectgroup-item d-block">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="User Management" @if (is_array($rolePermissions) && in_array('User Management', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button parent">{{ __('User Management') }}</span>
                                                    </label>
                                                    {{-- space management sub-menu start --}}
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="User Settings" @if (is_array($rolePermissions) && in_array('User Settings', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('User Settings') }}</span>
                                                    </label>
                                                    {{-- 'User Management sub-menu start --}}
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Registered Users"
                                                            @if (is_array($rolePermissions) && in_array('Registered Users', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Registered Users') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Subscribers" @if (is_array($rolePermissions) && in_array('Subscribers', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Subscribers') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="selectgroup-wrapper-item text-center">
                                                    <label class="selectgroup-item d-block">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Vendors Management"
                                                            @if (is_array($rolePermissions) && in_array('Vendors Management', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button parent">{{ __('Vendors Management') }}</span>
                                                    </label>
                                                    {{-- 'Vendor Management sub-menu start --}}
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Vendor Settings" @if (is_array($rolePermissions) && in_array('Vendor Settings', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Vendor Settings') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Registered Vendors"
                                                            @if (is_array($rolePermissions) && in_array('Registered Vendors', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Registered Vendors') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Add Vendor" @if (is_array($rolePermissions) && in_array('Add Vendor', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Add Vendor') }}</span>
                                                    </label>
                                                    {{-- 'vendor Management sub-menu end --}}
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="selectgroup-wrapper-item text-center">
                                                    <label class="selectgroup-item d-block">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Subscriptions Management"
                                                            @if (is_array($rolePermissions) && in_array('Subscriptions Management', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button parent">{{ __('Subscriptions Management') }}</span>
                                                    </label>
                                                    {{-- Subscriptions Management sub-menu start --}}
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Package Management"
                                                            @if (is_array($rolePermissions) && in_array('Package Management', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Package Management') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Subscription Logs"
                                                            @if (is_array($rolePermissions) && in_array('Subscription Logs', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Subscription Log') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="selectgroup-wrapper-item text-center">
                                                    <label class="selectgroup-item d-block">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Withdrawal Management"
                                                            @if (is_array($rolePermissions) && in_array('Withdrawal Management', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button parent">{{ __('Withdrawal Management') }}</span>
                                                    </label>

                                                    {{-- Withdrawal Management sub-menu start --}}
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Payment Methods" @if (is_array($rolePermissions) && in_array('Payment Methods', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Payment Methods') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Withdraw Requests"
                                                            @if (is_array($rolePermissions) && in_array('Withdraw Requests', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Withdraw Requests') }}</span>
                                                    </label>
                                                    {{-- Withdrawal Management sub-menu end --}}
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="selectgroup-wrapper-item text-center">
                                                    <label class="selectgroup-item d-block">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Pages" @if (is_array($rolePermissions) && in_array('Pages', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button parent">{{ __('Pages') }}</span>
                                                    </label>

                                                    {{-- Pages Management sub-menu start --}}
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Home Page" @if (is_array($rolePermissions) && in_array('Home Page', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Home Page') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="About Us" @if (is_array($rolePermissions) && in_array('About Us', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('About Us') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Menu Builder" @if (is_array($rolePermissions) && in_array('Menu Builder', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Menu Builder') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Footer" @if (is_array($rolePermissions) && in_array('Footer', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Footer') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Breadcrumbs" @if (is_array($rolePermissions) && in_array('Breadcrumbs', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Breadcrumbs') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="FAQs" @if (is_array($rolePermissions) && in_array('FAQs', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('FAQs') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Blogs" @if (is_array($rolePermissions) && in_array('Blogs', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Blog') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Contact Page" @if (is_array($rolePermissions) && in_array('Contact Page', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Contact Page') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Additional Pages"
                                                            @if (is_array($rolePermissions) && in_array('Additional Pages', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Additional Pages') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="SEO Information" @if (is_array($rolePermissions) && in_array('SEO Information', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('SEO Information') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="selectgroup-wrapper-item text-center">
                                                    {{-- Pages Management sub-menu start --}}
                                                    <label class="selectgroup-item d-block">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Shop Management" @if (is_array($rolePermissions) && in_array('Shop Management', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button parent">{{ __('Shop Management') }}</span>
                                                    </label>

                                                    {{-- Withdrawal Management sub-menu start --}}
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Shop Settings" @if (is_array($rolePermissions) && in_array('Shop Settings', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Shop Settings') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Tax Amounts" @if (is_array($rolePermissions) && in_array('Tax Amounts', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Tax Amounts') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Shipping Charges"
                                                            @if (is_array($rolePermissions) && in_array('Shipping Charges', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Shipping Charges') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Shop Coupons" @if (is_array($rolePermissions) && in_array('Shop Coupons', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Shop Coupons') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Manage Products" @if (is_array($rolePermissions) && in_array('Manage Products', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Manage Products') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Orders" @if (is_array($rolePermissions) && in_array('Orders', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Orders') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Shop Report" @if (is_array($rolePermissions) && in_array('Shop Report', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Report') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="selectgroup-wrapper-item text-center">
                                                    {{-- Withdrawal Management sub-menu start --}}
                                                    <label class="selectgroup-item d-block">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Transactions" @if (is_array($rolePermissions) && in_array('Transactions', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button parent">{{ __('Transactions') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Support Tickets" @if (is_array($rolePermissions) && in_array('Support Tickets', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Support Tickets') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Advertisements" @if (is_array($rolePermissions) && in_array('Advertisements', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Advertisements') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Announcement Popups"
                                                            @if (is_array($rolePermissions) && in_array('Announcement Popups', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Announcement Popups') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Settings" @if (is_array($rolePermissions) && in_array('Settings', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Settings') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="selectgroup-wrapper-item text-center">
                                                    {{-- Withdrawal Management sub-menu start --}}
                                                    <label class="selectgroup-item d-block">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="General Settings"
                                                            @if (is_array($rolePermissions) && in_array('General Settings', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button parent">{{ __('General Settings') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Email Settings" @if (is_array($rolePermissions) && in_array('Email Settings', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Email Settings') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Payment Gateways"
                                                            @if (is_array($rolePermissions) && in_array('Payment Gateways', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Payment Gateways') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Language Management"
                                                            @if (is_array($rolePermissions) && in_array('Language Management', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Language Management') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Plugins" @if (is_array($rolePermissions) && in_array('Plugins', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Plugins') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Maintenance Modes"
                                                            @if (is_array($rolePermissions) && in_array('Maintenance Modes', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Maintenance Modes') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Cookie Alerts" @if (is_array($rolePermissions) && in_array('Cookie Alerts', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Cookie Alerts') }}</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Social Media" @if (is_array($rolePermissions) && in_array('Social Media', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button">{{ __('Social Media') }}</span>
                                                    </label>
                                                    {{-- Withdrawal Management sub-menu start --}}
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="selectgroup-wrapper-item text-center">
                                                    <label class="selectgroup-item d-block">
                                                        <input type="checkbox" class="selectgroup-input" name="permissions[]"
                                                            value="Staffs Management"
                                                            @if (is_array($rolePermissions) && in_array('Staffs Management', $rolePermissions)) checked @endif>
                                                        <span class="selectgroup-button parent">{{ __('Staffs Management') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div><!-- row end -->
                                        
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
