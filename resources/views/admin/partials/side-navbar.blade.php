@php
    if (!is_null($roleInfo)) {
        $rolePermissions = json_decode($roleInfo->permissions) ?? [];
    } else {
        $rolePermissions = [];
    }

    $shouldDisplaySpace = has_permission_group('space_management', $rolePermissions, $roleInfo);
    $shouldDisplayBookingAndRequest = has_permission_group('bookings_requests', $rolePermissions, $roleInfo);
    $shouldDisplayUser = has_permission_group('user_management', $rolePermissions, $roleInfo);
    $shouldDisplayVendor = has_permission_group('vendors_management', $rolePermissions, $roleInfo);
    $shouldDisplaySubscription = has_permission_group('subscriptions_management', $rolePermissions, $roleInfo);
    $shouldDisplayWithdraw = has_permission_group('withdrawal_management', $rolePermissions, $roleInfo);
    $shouldDisplayPages = has_permission_group('pages', $rolePermissions, $roleInfo);
    $shouldDisplayShopManagement = has_permission_group('shop_management', $rolePermissions, $roleInfo);
    $shouldDisplayTransaction = has_permission_group('transaction', $rolePermissions, $roleInfo);
    $shouldDisplaySupportTickets = has_permission_group('support_tickets', $rolePermissions, $roleInfo);
    $shouldDisplayAdvertisements = has_permission_group('advertisements', $rolePermissions, $roleInfo);
    $shouldDisplayAnnouncementPopups = has_permission_group('announcement_popups', $rolePermissions, $roleInfo);
    $shouldDisplaySettings = has_permission_group('settings', $rolePermissions, $roleInfo);
    $shouldDisplayStaffsManagement = has_permission_group('staffs_management', $rolePermissions, $roleInfo);
@endphp


<div class="sidebar sidebar-style-2"
    data-background-color="{{ $settings->admin_theme_version == 'light' ? 'white' : 'dark2' }}">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    @if (Auth::guard('admin')->user()->image != null)
                        <img src="{{ asset('assets/img/admins/' . Auth::guard('admin')->user()->image) }}"
                            alt="Admin Image" class="avatar-img rounded-circle">
                    @else
                        <img src="{{ asset('assets/img/blank-user.jpg') }}" alt=""
                            class="avatar-img rounded-circle">
                    @endif
                </div>

                <div class="info">
                    <a data-toggle="collapse" href="#adminProfileMenu" aria-expanded="true">
                        <span>
                            {{ Auth::guard('admin')->user()->first_name }}

                            @if (is_null($roleInfo))
                                <span class="user-level">{{ __('Super admin') }}</span>
                            @else
                                <span class="user-level">{{ $roleInfo->name }}</span>
                            @endif

                            <span class="caret"></span>
                        </span>
                    </a>

                    <div class="clearfix"></div>

                    <div class="collapse in" id="adminProfileMenu">
                        <ul class="nav">
                            <li>
                                <a href="{{ route('admin.account.edit_profile') }}?language={{ $defaultLang->code }}">
                                    <span class="link-collapse">{{ __('Edit Profile') }}</span>
                                </a>
                            </li>

                            <li>
                                <a
                                    href="{{ route('admin.account.change_password') }}?language={{ $defaultLang->code }}">
                                    <span class="link-collapse">{{ __('Change Password') }}</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.account.logout') }}">
                                    <span class="link-collapse">{{ __('Logout') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <ul class="nav nav-primary">
                {{-- search --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="">
                            <input type="hidden" name="language" value="{{ $defaultLang->code }}">

                            <div class="form-group py-0">
                                <input name="term" type="text" class="form-control sidebar-search "
                                    placeholder="{{ __('Search Menu Here...') }}"
                                    style="direction: {{ $defaultLang->direction == 0 ? 'ltr' : 'rtl' }};">
                            </div>
                        </form>
                    </div>
                </div>

                {{-- dashboard --}}
                <li class="nav-item @if (route_group_is_active('dashboard')) active @endif">
                    <a href="{{ route('admin.dashboard', ['language' => $defaultLang->code]) }}">
                        <i class="la flaticon-paint-palette"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>

                {{-- space Management --}}
                @if ($shouldDisplaySpace)
                    <li class="nav-item @if (route_group_is_active('space_management_group')) active @endif">

                        <a data-toggle="collapse" href="#service">
                            <i class="fas fa-info-circle"></i>
                            <p>{{ __('Space Management') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div id="service" class="collapse @if (route_group_is_active('space_management_group')) show @endif">
                            <ul class="nav nav-collapse">
                                @if (has_child_permission_only('Space Settings', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('space_settings') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.space-management.space.settings', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Settings') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Holidays', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('holidays') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.holiday.select_vendor', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Holidays') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Coupons', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('coupons') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.space_management.coupons.index', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Coupons') }}</span>
                                        </a>
                                    </li>
                                @endif


                                @if (has_child_permission_only('Specifications', $rolePermissions, $roleInfo))
                                    <!-- Specifications Submenu -->
                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#specifications"
                                            aria-expanded="{{ route_group_is_active('specifications') ? 'true' : 'false' }}">
                                            <span class="sub-item">{{ __('Specifications') }}</span>
                                            <span class="caret"></span>
                                        </a>

                                        <div id="specifications"
                                            class="collapse @if (route_group_is_active('specifications')) show @endif">
                                            <ul class="nav nav-collapse subnav">
                                                <li class="{{ route_group_is_active('amenities') ? 'active' : '' }}">

                                                    <a
                                                        href="{{ route('admin.space_management.amenities.index', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Amenities') }}</span>
                                                    </a>
                                                </li>

                                                <li class="submenu">
                                                    <a data-toggle="collapse" href="#locationManagement"
                                                        aria-expanded="{{ route_group_is_active('locations') ? 'true' : 'false' }}">
                                                        <span class="sub-item">{{ __('Locations') }}</span>
                                                        <span class="caret"></span>
                                                    </a>

                                                    <div id="locationManagement"
                                                        class="collapse @if (route_group_is_active('locations')) show @endif">
                                                        <ul class="nav nav-collapse subnav ml-4">
                                                            <li
                                                                class="{{ route_group_is_active('countries') ? 'active' : '' }}">

                                                                <a
                                                                    href="{{ route('admin.location_management.country.index', ['language' => $defaultLang->code]) }}">
                                                                    <span class="sub-item">{{ __('Countries') }}</span>
                                                                </a>
                                                            </li>

                                                            <li
                                                                class="{{ route_group_is_active('states') ? 'active' : '' }}">
                                                                <a
                                                                    href="{{ route('admin.location_management.state.index', ['language' => $defaultLang->code]) }}">
                                                                    <span class="sub-item">{{ __('States') }}</span>
                                                                </a>
                                                            </li>
                                                            <li
                                                                class="{{ route_group_is_active('cities') ? 'active' : '' }}">
                                                                <a
                                                                    href="{{ route('admin.location_management.city.index', ['language' => $defaultLang->code]) }}">
                                                                    <span class="sub-item">{{ __('Cities') }}</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>

                                                <li class="{{ route_group_is_active('categories') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.space_management.space-category.index', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Categories') }}</span>
                                                    </a>
                                                </li>

                                                <li
                                                    class="{{ route_group_is_active('subcategories') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.space_management.sub-category.index', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Subcategories') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <!-- End Specifications Submenu -->
                                @endif

                                <!-- Featured Management Submenu start-->
                                @if (has_child_permission_only('Featured Management', $rolePermissions, $roleInfo))
                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#feature_management"
                                            aria-expanded="{{ route_group_is_active('featured_management') ? 'true' : 'false' }}">
                                            <span class="sub-item">{{ __('Featured Management') }}</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div id="feature_management"
                                            class="collapse @if (route_group_is_active('featured_management')) show @endif">
                                            <ul class="nav nav-collapse subnav">
                                                <li
                                                    class="{{ route_group_is_active('featured_charges') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.feature_record.charge.index', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Charges') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('all_featured_requests') && empty(request()->input('feature_status')) ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.feature_record.index', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('All Requests') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('pending_featured_requests') && request()->input('feature_status') == 'pending' ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.feature_record.index', ['feature_status' => 'pending', 'language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Pending Requests') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('approved_featured_requests') && request()->input('feature_status') == 'approved' ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.feature_record.index', ['feature_status' => 'approved', 'language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Approved Requests') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('rejected_featured_requests') && request()->input('feature_status') == 'rejected' ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.feature_record.index', ['feature_status' => 'rejected', 'language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Rejected Requests') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif
                                <!-- Featured Management Submenu end-->

                                <!-- Form start here-->
                                @if (has_child_permission_only('Forms', $rolePermissions, $roleInfo))
                                    <li class="@if (route_group_is_active('forms')) active @endif">
                                        <a
                                            href="{{ route('admin.space-management.form.index', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Forms') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <!-- Form end here-->

                                <!-- space start here-->
                                @if (has_child_permission_only('Spaces', $rolePermissions, $roleInfo))
                                    <li class="@if (route_group_is_active('spaces')) active @endif">
                                        <a
                                            href="{{ route('admin.space_management.space.index', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Spaces') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <!-- space end here-->
                            </ul>
                        </div>
                    </li>
                @endif


                {{-- "Bookings & Requests" navigation item start --}}

                @if ($shouldDisplayBookingAndRequest)
                    <li class="nav-item @if (route_group_is_active('bookings_requests_group')) active @endif">
                        <a data-toggle="collapse" href="#bookings_requests">
                            <i class="fas fa-calendar-check"></i>
                            <p>{{ __('Bookings & Requests') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="bookings_requests" class="collapse @if (route_group_is_active('bookings_requests_group')) show @endif">
                            <ul class="nav nav-collapse">
                                <!-- Quote Requests Submenu -->
                                @if (has_child_permission_only('Quote Requests', $rolePermissions, $roleInfo))
                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#quote_request_management"
                                            aria-expanded="{{ route_group_is_active('quote_requests') ? 'true' : 'false' }}">
                                            <span class="sub-item">{{ __('Quote Requests') }}</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div id="quote_request_management"
                                            class="collapse @if (route_group_is_active('quote_requests')) show @endif">
                                            <ul class="nav nav-collapse subnav">
                                                <li
                                                    class="{{ route_group_is_active('all_quote_requests') && empty(request()->input('quote_status')) ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.space.form.get_quote.index', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('All Requests') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('pending_quote_requests') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.space.form.get_quote.index', ['quote_status' => 'pending', 'language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Pending Requests') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('responded_quote_requests') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.space.form.get_quote.index', ['quote_status' => 'responded', 'language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Responded Requests') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('in_progress_quote_requests') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.space.form.get_quote.index', ['quote_status' => 'in_progress', 'language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('In Progress Requests') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('closed_quote_requests') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.space.form.get_quote.index', ['quote_status' => 'closed', 'language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Closed Requests') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('cancelled_quote_requests') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.space.form.get_quote.index', ['quote_status' => 'cancelled', 'language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Cancelled Requests') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif

                                <!-- Tour Requests Submenu -->
                                @if (has_child_permission_only('Tour Requests', $rolePermissions, $roleInfo))
                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#tour_request_management"
                                            aria-expanded="{{ route_group_is_active('tour_requests') ? 'true' : 'false' }}">
                                            <span class="sub-item">{{ __('Tour Requests') }}</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div id="tour_request_management"
                                            class="collapse @if (route_group_is_active('tour_requests')) show @endif">
                                            <ul class="nav nav-collapse subnav">
                                                <li
                                                    class="{{ route_group_is_active('all_tour_requests') && empty(request()->input('tour_status')) ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.space.form.tour_request.index', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('All Requests') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('pending_tour_requests') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.space.form.tour_request.index', ['tour_status' => 'pending', 'language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Pending Requests') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('confirmed_tour_requests') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.space.form.tour_request.index', ['tour_status' => 'confirmed', 'language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Confirmed Requests') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('closed_tour_requests') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.space.form.tour_request.index', ['tour_status' => 'closed', 'language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Closed Requests') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('cancelled_tour_requests') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.space.form.tour_request.index', ['tour_status' => 'cancelled', 'language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Cancelled Requests') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif

                                <!-- Booking Management Submenu -->
                                @if (has_child_permission_only('Booking Management', $rolePermissions, $roleInfo))
                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#booking_management"
                                            aria-expanded="{{ route_group_is_active('booking_management') ? 'true' : 'false' }}">
                                            <span class="sub-item">{{ __('Booking Management') }}</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div id="booking_management"
                                            class="collapse @if (route_group_is_active('booking_management')) show @endif">
                                            <ul class="nav nav-collapse subnav">
                                                <li class="@if (route_group_is_active('add_booking')) active @endif">
                                                    <a
                                                        href="{{ route('admin.add_booking.space_selection', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Add Booking') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('all_bookings') && empty(request()->input('booking_status')) ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.booking_record.index', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('All Bookings') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('pending_bookings') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.booking_record.index', ['booking_status' => 'pending', 'language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Pending Bookings') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('approved_bookings') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.booking_record.index', ['booking_status' => 'approved', 'language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Approved Bookings') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('rejected_bookings') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.booking_record.index', ['booking_status' => 'rejected', 'language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Rejected Bookings') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('booking_report') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.booking_record.booking_report', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Report') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- User Management start --}}
                @if ($shouldDisplayUser)
                    <li class="nav-item @if (route_group_is_active('user_management_group')) active @endif">
                        <a data-toggle="collapse" href="#user">
                            <i class="la flaticon-users"></i>
                            <p>{{ __('User Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="user" class="collapse @if (route_group_is_active('user_management_group')) show @endif">
                            <ul class="nav nav-collapse">
                                @if (has_child_permission_only('User Settings', $rolePermissions, $roleInfo))
                                    <li class="@if (route_group_is_active('user_settings')) active @endif">
                                        <a
                                            href="{{ route('admin.user_management.registered_users.setting', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Settings') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Registered Users', $rolePermissions, $roleInfo))
                                    <li class="@if (route_group_is_active('registered_users')) active @endif">
                                        <a
                                            href="{{ route('admin.user_management.registered_users', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Registered Users') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Subscribers', $rolePermissions, $roleInfo))
                                    <li class="@if (route_group_is_active('subscribers')) active @endif">
                                        <a
                                            href="{{ route('admin.user_management.subscribers', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Subscribers') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif
                {{-- User Management end --}}

                {{-- Vendor Management start --}}

                @if ($shouldDisplayVendor)
                    <li class="nav-item @if (route_group_is_active('vendors_management_group')) active @endif">
                        <a data-toggle="collapse" href="#vendor">
                            <i class="fas fa-user-tie"></i>
                            <p>{{ __('Vendors Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="vendor" class="collapse @if (route_group_is_active('vendors_management_group')) show @endif">
                            <ul class="nav nav-collapse">

                                @if (has_child_permission_only('Vendor Settings', $rolePermissions, $roleInfo))
                                    <li class="@if (route_group_is_active('vendor_settings')) active @endif">
                                        <a
                                            href="{{ route('admin.end-user.vendor.settings', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Settings') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Registered Vendors', $rolePermissions, $roleInfo))
                                    <li class="@if (route_group_is_active('registered_vendors')) active @endif">
                                        <a
                                            href="{{ route('admin.end-user.vendor.registered_vendor', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Registered Vendors') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Add Vendor', $rolePermissions, $roleInfo))
                                    <li class="@if (route_group_is_active('add_vendor')) active @endif">
                                        <a
                                            href="{{ route('admin.end-user.vendor.add', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Add Vendor') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif
                {{-- Vendor Management end --}}

                {{-- Package Management and Subscription Logs start --}}

                @if ($shouldDisplaySubscription)
                    <li class="nav-item @if (route_group_is_active('subscriptions_management_group')) active @endif">
                        <a data-toggle="collapse" href="#subscriptionsManagement">
                            <i class="fas fa-credit-card"></i>
                            <p>{{ __('Subscriptions Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="subscriptionsManagement"
                            class="collapse @if (route_group_is_active('subscriptions_management_group')) show @endif">
                            <ul class="nav nav-collapse">

                                @if (has_child_permission_only('Package Management', $rolePermissions, $roleInfo))
                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#packageManagement"
                                            aria-expanded="{{ route_group_is_active('package_management') ? 'true' : 'false' }}">
                                            <span class="sub-item">{{ __('Package Management') }}</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div id="packageManagement"
                                            class="collapse @if (route_group_is_active('package_management')) show @endif">
                                            <ul class="nav nav-collapse subnav pl-3">
                                                <li
                                                    class="{{ route_group_is_active('package_settings') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.package.settings', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Settings') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('package_features') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.package.features', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Package Features') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('packages_list') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.package.index', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Packages') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Subscription Logs', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('subscription_logs') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.payment-log.index', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Subscription Log') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif
                {{-- Package Management and Subscription Logs end --}}

                {{-- Withdrawals Management start --}}

                @if ($shouldDisplayWithdraw)
                    <li class="nav-item @if (route_group_is_active('withdrawal_management_group')) active @endif">
                        <a data-toggle="collapse" href="#withdraw_method">
                            <i class="fal fa-credit-card"></i>
                            <p>{{ __('Withdrawal Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="withdraw_method" class="collapse @if (route_group_is_active('withdrawal_management_group')) show @endif">
                            <ul class="nav nav-collapse">

                                @if (has_child_permission_only('Payment Methods', $rolePermissions, $roleInfo))
                                    <li class="@if (route_group_is_active('payment_methods') && empty(request()->input('status'))) active @endif">
                                        <a
                                            href="{{ route('admin.withdraw.payment_method', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Payment Methods') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Withdraw Requests', $rolePermissions, $roleInfo))
                                    <li class="@if (route_group_is_active('withdraw_requests') && empty(request()->input('status'))) active @endif">
                                        <a
                                            href="{{ route('admin.withdraw.withdraw_request', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Withdraw Requests') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif
                {{-- Withdrawals Management end --}}

                {{-- website pages start --}}

                @if ($shouldDisplayPages)
                    <li class="nav-item @if (route_group_is_active('pages_group')) active @endif">
                        <a data-toggle="collapse" href="#pages">
                            <i class="la flaticon-file"></i>
                            <p>{{ __('Pages') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="pages" class="collapse @if (route_group_is_active('pages_group')) show @endif">
                            <ul class="nav nav-collapse">

                                {{-- Home page --}}
                                @if (has_child_permission_only('Home Page', $rolePermissions, $roleInfo))
                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#home-page"
                                            aria-expanded="{{ route_group_is_active('home_page') ? 'true' : 'false' }}">
                                            <span class="sub-item">{{ __('Home Page') }}</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div id="home-page"
                                            class="collapse @if (route_group_is_active('home_page')) show @endif">
                                            <ul class="nav nav-collapse subnav">
                                                <li
                                                    class="{{ route_group_is_active('section_customization') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.home_page.section_customization', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Section Hide/Show') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('section_content') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.home_page.section_content', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Images & Texts') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('work_process_section') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.home_page.work_process_section', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Work Process') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('testimonials_section') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.home_page.testimonials_section', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Testimonials') }}</span>
                                                    </a>
                                                </li>

                                                {{-- Additional Sections --}}
                                                <li class="submenu">
                                                    <a data-toggle="collapse" href="#additional-sections"
                                                        aria-expanded="{{ route_group_is_active('additional_sections') ? 'true' : 'false' }}">
                                                        <span class="sub-item">{{ __('Additional Sections') }}</span>
                                                        <span class="caret"></span>
                                                    </a>
                                                    <div id="additional-sections"
                                                        class="collapse @if (route_group_is_active('additional_sections')) show @endif">
                                                        <ul class="nav nav-collapse subnav">
                                                            <li
                                                                class="{{ request()->routeIs('admin.home_page.additional_sections.create') ? 'active' : '' }}">
                                                                <a
                                                                    href="{{ route('admin.home_page.additional_sections.create', ['language' => $defaultLang->code]) }}">
                                                                    <span
                                                                        class="sub-item">{{ __('Add Section') }}</span>
                                                                </a>
                                                            </li>
                                                            <li
                                                                class="{{ route_group_is_active('sections') ? 'active' : '' }}">
                                                                <a
                                                                    href="{{ route('admin.home_page.additional_sections.index', ['language' => $defaultLang->code]) }}">
                                                                    <span
                                                                        class="sub-item">{{ __('Sections') }}</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif

                                {{-- About us --}}
                                @if (has_child_permission_only('About Us', $rolePermissions, $roleInfo))
                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#about-us-page"
                                            aria-expanded="{{ route_group_is_active('about_us') ? 'true' : 'false' }}">
                                            <span class="sub-item">{{ __('About Us') }}</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div id="about-us-page"
                                            class="collapse @if (route_group_is_active('about_us')) show @endif">
                                            <ul class="nav nav-collapse subnav">
                                                <li
                                                    class="{{ route_group_is_active('about_section') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.home_page.about_section', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Heading') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('about_content') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.about_us.about_content.index', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('About Content') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif

                                {{-- Menu Builder --}}
                                @if (has_child_permission_only('Menu Builder', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('menu_builder') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.menu_builder', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Menu Builder') }}</span>
                                        </a>
                                    </li>
                                @endif

                                {{-- Footer page --}}
                                @if (has_child_permission_only('Footer', $rolePermissions, $roleInfo))
                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#footer-page"
                                            aria-expanded="{{ route_group_is_active('footer') ? 'true' : 'false' }}">
                                            <span class="sub-item">{{ __('Footer') }}</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div id="footer-page"
                                            class="collapse @if (route_group_is_active('footer')) show @endif">
                                            <ul class="nav nav-collapse subnav">
                                                <li
                                                    class="{{ route_group_is_active('footer_content') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.footer.content', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Content') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('footer_quick_links') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.footer.quick_links', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Quick Links') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif

                                {{-- Breadcrumb --}}
                                @if (has_child_permission_only('Breadcrumbs', $rolePermissions, $roleInfo))
                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#breadcrumb-settings"
                                            aria-expanded="{{ route_group_is_active('breadcrumb') ? 'true' : 'false' }}">
                                            <span class="sub-item">{{ __('Breadcrumb') }}</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div id="breadcrumb-settings"
                                            class="collapse @if (route_group_is_active('breadcrumb')) show @endif">
                                            <ul class="nav nav-collapse subnav">
                                                <li
                                                    class="{{ route_group_is_active('breadcrumb_image') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.breadcrumb.image') }}">
                                                        <span class="sub-item">{{ __('Image') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('page_headings') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.basic_settings.page_headings', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Headings') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif

                                {{-- faq --}}
                                @if (has_child_permission_only('FAQs', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('faqs') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.faq_management', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('FAQs') }}</span>
                                        </a>
                                    </li>
                                @endif

                                {{-- Blog --}}
                                @if (has_child_permission_only('Blogs', $rolePermissions, $roleInfo))
                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#blog-page"
                                            aria-expanded="{{ route_group_is_active('blog') ? 'true' : 'false' }}">
                                            <span class="sub-item">{{ __('Blog') }}</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div id="blog-page"
                                            class="collapse @if (route_group_is_active('blog')) show @endif">
                                            <ul class="nav nav-collapse subnav">
                                                <li
                                                    class="{{ route_group_is_active('blog_categories') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.blog_management.categories', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Categories') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('blog_posts') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.blog_management.posts', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Posts') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif

                                {{-- Contact Page --}}
                                @if (has_child_permission_only('Contact Page', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('contact_page') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.home_page.contact.index', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Contact Page') }}</span>
                                        </a>
                                    </li>
                                @endif

                                {{-- custom page --}}
                                @if (has_child_permission_only('Additional Pages', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('custom_pages') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.custom_pages', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Additional Pages') }}</span>
                                        </a>
                                    </li>
                                @endif

                                {{-- SEO Information --}}
                                @if (has_child_permission_only('SEO Information', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('seo_information') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.basic_settings.seo', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('SEO Information') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- website pages end --}}

                {{-- Shop Management start --}}


                @if ($shouldDisplayShopManagement)
                    <li class="nav-item @if (route_group_is_active('shop_management_group')) active @endif">
                        <a data-toggle="collapse" href="#shop">
                            <i class="fal fa-store-alt"></i>
                            <p>{{ __('Shop Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="shop" class="collapse @if (route_group_is_active('shop_management_group')) show @endif">
                            <ul class="nav nav-collapse">

                                @if (has_child_permission_only('Shop Settings', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('shop_settings') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.shop_management.settings', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Settings') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Tax Amounts', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('tax_amounts') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.shop_management.tax_amount', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Tax Amounts') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Shipping Charges', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('shipping_charges') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.shop_management.shipping_charges', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Shipping Charges') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Shop Coupons', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('shop_coupons') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.shop_management.coupons', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Coupons') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Manage Products', $rolePermissions, $roleInfo))
                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#product"
                                            aria-expanded="{{ route_group_is_active('manage_products') ? 'true' : 'false' }}">
                                            <span class="sub-item">{{ __('Manage Products') }}</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div id="product"
                                            class="collapse @if (route_group_is_active('manage_products')) show @endif">
                                            <ul class="nav nav-collapse subnav">
                                                <li
                                                    class="{{ route_group_is_active('product_categories') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.shop_management.product.categories', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Categories') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('products_list') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.shop_management.products', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Products') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Orders', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('orders') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.shop_management.orders', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Orders') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Shop Report', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('shop_report') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.shop_management.report', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Report') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- Transaction --}}

                @if ($shouldDisplayTransaction)
                    <li class="nav-item @if (route_group_is_active('transactions')) active @endif">
                        <a href="{{ route('admin.dashboard.transaction', ['language' => $defaultLang->code]) }}">
                            <i class="fal fa-exchange-alt"></i>
                            <p>{{ __('Transactions') }}</p>
                        </a>
                    </li>
                @endif

                {{-- support ticket route start --}}
                @if ($shouldDisplaySupportTickets)
                    <li class="nav-item @if (route_group_is_active('support_tickets_group')) active @endif">
                        <a data-toggle="collapse" href="#support_tickets">
                            <i class="fal fa-ticket-alt"></i>
                            <p>{{ __('Support Tickets') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="support_tickets" class="collapse @if (route_group_is_active('support_tickets_group')) show @endif">
                            <ul class="nav nav-collapse">
                                <li
                                    class="{{ route_group_is_active('all_support_tickets') && empty(request()->input('ticket_status')) ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.support_tickets', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('All Tickets') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ route_group_is_active('pending_support_tickets') && request()->input('ticket_status') == 'pending' ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.support_tickets', ['ticket_status' => 'pending', 'language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Pending Ticket') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ route_group_is_active('open_support_tickets') && request()->input('ticket_status') == 'open' ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.support_tickets', ['ticket_status' => 'open', 'language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Open Tickets') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ route_group_is_active('closed_support_tickets') && request()->input('ticket_status') == 'closed' ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.support_tickets', ['ticket_status' => 'closed', 'language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Closed Tickets') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- support ticket route end --}}

                {{-- advertisement route start here --}}

                @if ($shouldDisplayAdvertisements)
                    <li class="nav-item @if (route_group_is_active('advertisements_group')) active @endif">
                        <a data-toggle="collapse" href="#abecex">
                            <i class="fab fa-buysellads"></i>
                            <p>{{ __('Advertisements') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="abecex" class="collapse @if (route_group_is_active('advertisements_group')) show @endif">
                            <ul class="nav nav-collapse">
                                <li class="{{ route_group_is_active('advertise_settings') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.advertise.settings', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Settings') }}</span>
                                    </a>
                                </li>
                                <li class="{{ route_group_is_active('all_advertisements') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('admin.advertise.all_advertisement', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('All Advertisements') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                {{-- advertisement route start here --}}

                {{-- announcement popup start --}}

                @if ($shouldDisplayAnnouncementPopups)
                    <li class="nav-item @if (route_group_is_active('announcement_popups_group')) active @endif">
                        <a href="{{ route('admin.announcement_popups', ['language' => $defaultLang->code]) }}">
                            <i class="fal fa-bullhorn"></i>
                            <p>{{ __('Announcement Popups') }}</p>
                        </a>
                    </li>
                @endif
                {{-- announcement popup end --}}

                {{-- settings route start --}}

                @if ($shouldDisplaySettings)
                    <li class="nav-item @if (route_group_is_active('settings_group')) active @endif">
                        <a data-toggle="collapse" href="#basic_settings">
                            <i class="la flaticon-settings"></i>
                            <p>{{ __('Settings') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="basic_settings" class="collapse @if (route_group_is_active('settings_group')) show @endif">
                            <ul class="nav nav-collapse">

                                @if (has_child_permission_only('General Settings', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('general_settings') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.basic_settings.general_settings', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('General Settings') }}</span>
                                        </a>
                                    </li>
                                @endif

                                {{-- email settings --}}
                                @if (has_child_permission_only('Email Settings', $rolePermissions, $roleInfo))
                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#mail-settings"
                                            aria-expanded="{{ route_group_is_active('email_settings_group') ? 'true' : 'false' }}">
                                            <span class="sub-item">{{ __('Email Settings') }}</span>
                                            <span class="caret"></span>
                                        </a>

                                        <div id="mail-settings"
                                            class="collapse @if (route_group_is_active('email_settings_group')) show @endif">
                                            <ul class="nav nav-collapse subnav">
                                                <li
                                                    class="{{ route_group_is_active('mail_from_admin') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.basic_settings.mail_from_admin', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Mail From admin') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('mail_to_admin') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.basic_settings.mail_to_admin', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Mail To admin') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('mail_templates') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.basic_settings.mail_templates', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Mail Templates') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Payment Gateways', $rolePermissions, $roleInfo))
                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#payment-gateway"
                                            aria-expanded="{{ route_group_is_active('payment_gateways_group') ? 'true' : 'false' }}">
                                            <span class="sub-item">{{ __('Payment Gateways') }}</span>
                                            <span class="caret"></span>
                                        </a>

                                        <div id="payment-gateway"
                                            class="collapse @if (route_group_is_active('payment_gateways_group')) show @endif">
                                            <ul class="nav nav-collapse subnav">
                                                <li
                                                    class="{{ route_group_is_active('online_gateways') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.payment_gateways.online_gateways', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Online Gateways') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ route_group_is_active('offline_gateways') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.payment_gateways.offline_gateways', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Offline Gateways') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Language Management', $rolePermissions, $roleInfo))
                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#language_management"
                                            aria-expanded="{{ route_group_is_active('language_management_group') ? 'true' : 'false' }}">
                                            <span class="sub-item">{{ __('Language Management') }}</span>
                                            <span class="caret"></span>
                                        </a>

                                        <div id="language_management"
                                            class="collapse @if (route_group_is_active('language_management_group')) show @endif">
                                            <ul class="nav nav-collapse subnav">
                                                <li
                                                    class="{{ route_group_is_active('language_settings') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.language_management.settings', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Settings') }}</span>
                                                    </a>
                                                </li>
                                                <li class="{{ route_group_is_active('languages') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ route('admin.language_management', ['language' => $defaultLang->code]) }}">
                                                        <span class="sub-item">{{ __('Languages') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Plugins', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('plugins') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.basic_settings.plugins', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Plugins') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Maintenance Modes', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('maintenance_mode') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.basic_settings.maintenance_mode', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Maintenance Modes') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Cookie Alerts', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('cookie_alert') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.basic_settings.cookie_alert', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Cookie Alerts') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (has_child_permission_only('Social Media', $rolePermissions, $roleInfo))
                                    <li class="{{ route_group_is_active('social_media') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('admin.basic_settings.social_medias', ['language' => $defaultLang->code]) }}">
                                            <span class="sub-item">{{ __('Social Media') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- settings route end --}}



                {{-- Staff management route start --}}

                @if ($shouldDisplayStaffsManagement)
                    <li class="nav-item @if (route_group_is_active('staffs_management_group')) active @endif">
                        <a data-toggle="collapse" href="#admin">
                            <i class="fal fa-users-cog"></i>
                            <p>{{ __('Staffs Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="admin" class="collapse @if (route_group_is_active('staffs_management_group')) show @endif">
                            <ul class="nav nav-collapse">
                                <li class="@if (route_group_is_active('role_permissions')) active @endif">
                                    <a
                                        href="{{ route('admin.admin_management.role_permissions', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Role & Permissions') }}</span>
                                    </a>
                                </li>
                                <li class="@if (route_group_is_active('registered_staffs')) active @endif">
                                    <a
                                        href="{{ route('admin.admin_management.registered_admins', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Registered Staffs') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
