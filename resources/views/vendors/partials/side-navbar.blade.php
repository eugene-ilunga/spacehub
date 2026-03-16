<div class="sidebar sidebar-style-2"
    data-background-color="{{ Session::get('seller_theme_version') == 'light' ? 'white' : 'dark2' }}">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    @if (Auth::guard('seller')->user()->photo != null)
                        <img src="{{ asset('assets/admin/img/seller-photo/' . Auth::guard('seller')->user()->photo) }}"
                            alt="Seller Image" class="avatar-img rounded-circle">
                    @else
                        <img src="{{ asset('assets/frontend/images/vendor/vendor.png') }}" alt=""
                            class="avatar-img rounded-circle">
                    @endif
                </div>

                <div class="info">
                    <a data-toggle="collapse" href="#adminProfileMenu" aria-expanded="true">
                        <span>
                            {{ Auth::guard('seller')->user()->username }}
                            <span class="user-level">{{ __('Vendor') }}</span>
                            <span class="caret"></span>
                        </span>
                    </a>

                    <div class="clearfix"></div>

                    <div class="collapse in" id="adminProfileMenu">
                        <ul class="nav">
                            <li>
                                <a href="{{ route('vendor.edit.profile', ['language' => $defaultLang->code]) }}">
                                    <span class="link-collapse">{{ __('Edit Profile') }}</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('vendor.change_password', ['language' => $defaultLang->code]) }}">
                                    <span class="link-collapse">{{ __('Change Password') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('vendor.logout', ['language' => $defaultLang->code]) }}">
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
                        <form>
                            <input type="hidden" name="language" value="{{ $defaultLang->code }}">

                            <div class="form-group py-0">
                                <input name="term" type="text" class="form-control sidebar-search"
                                    placeholder="{{ __('Search Menu Here...') }}">
                            </div>
                        </form>
                    </div>
                </div>
                @php
                    $seller = Auth::guard('seller')->user();
                    $package = \App\Http\Helpers\SellerPermissionHelper::currentPackagePermission($seller->id);
                @endphp
                {{-- dashboard --}}
                <li class="nav-item @if (request()->routeIs('vendor.dashboard')) active @endif">
                    <a href="{{ route('vendor.dashboard', ['language' => $defaultLang->code]) }}">
                        <i class="fal fa-tachometer-alt-average"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>

                <li class="nav-item {{ route_group_is_active('space_management_group') ? 'active' : '' }}">
                    <a data-toggle="collapse" href="#space">
                        <i class="fas fa-info-circle"></i>
                        <p>{{ __('Space Management') }}</p>
                        <span class="caret"></span>
                    </a>

                    <div id="space"
                        class="collapse {{ route_group_is_active('space_management_group') ? 'show' : '' }}">
                        <ul class="nav nav-collapse">
                            <li class="{{ route_group_is_active('holidays') ? 'active' : '' }}">
                                <a href="{{ route('vendor.holiday.index', ['language' => $defaultLang->code]) }}">
                                    <span class="sub-item">{{ __('Holidays') }}</span>
                                </a>
                            </li>

                            <li class="{{ route_group_is_active('forms') ? 'active' : '' }}">
                                <a
                                    href="{{ route('vendor.space_management.form.index', ['language' => $defaultLang->code]) }}">
                                    <span class="sub-item">{{ __('Forms') }}</span>
                                </a>
                            </li>

                            <li class="{{ route_group_is_active('coupons') ? 'active' : '' }}">
                                <a
                                    href="{{ route('vendor.space_management.coupons.index', ['language' => $defaultLang->code]) }}">
                                    <span class="sub-item">{{ __('Coupons') }}</span>
                                </a>
                            </li>

                            <li class=" {{ route_group_is_active('spaces') ? 'active' : '' }}">
                                <a
                                    href="{{ route('vendor.space_management.space.index', ['language' => $defaultLang->code]) }}">
                                    <span class="sub-item">{{ __('Spaces') }}</span>
                                </a>
                            </li>


                        </ul>
                    </div>
                </li>
                {{-- space Management end --}}

                @php
                    $seller_id = \Illuminate\Support\Facades\Auth::guard('seller')->user()->id;
                    $permissions = \App\Http\Helpers\SellerPermissionHelper::currentPackagePermission($seller_id);
                    if (!empty($permissions)) {
                        $permissions = json_decode($permissions->package_feature, true);
                        $permissions = is_array($permissions) ? $permissions : [];
                    } else {
                        $permissions = [];
                    }
                @endphp

                {{-- "Bookings & Requests" navigation item start --}}

                <li class="nav-item @if (route_group_is_active('bookings_requests_group')) active @endif">
                    <a data-toggle="collapse" href="#bookings_requests">
                        <i class="fas fa-calendar-check"></i>
                        <p>{{ __('Bookings & Requests') }}</p>
                        <span class="caret"></span>
                    </a>

                    <div id="bookings_requests" class="collapse @if (route_group_is_active('bookings_requests_group')) show @endif">
                        <ul class="nav nav-collapse">
                            <!-- Quote Requests Submenu -->
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
                                                href="{{ route('vendor.space.form.get_quote.index', ['language' => $defaultLang->code]) }}">
                                                <span class="sub-item">{{ __('All Requests') }}</span>
                                            </a>
                                        </li>
                                        <li
                                            class="{{ route_group_is_active('pending_quote_requests') ? 'active' : '' }}">
                                            <a
                                                href="{{ route('vendor.space.form.get_quote.index', ['quote_status' => 'pending', 'language' => $defaultLang->code]) }}">
                                                <span class="sub-item">{{ __('Pending Requests') }}</span>
                                            </a>
                                        </li>
                                        <li
                                            class="{{ route_group_is_active('responded_quote_requests') ? 'active' : '' }}">
                                            <a
                                                href="{{ route('vendor.space.form.get_quote.index', ['quote_status' => 'responded', 'language' => $defaultLang->code]) }}">
                                                <span class="sub-item">{{ __('Responded Requests') }}</span>
                                            </a>
                                        </li>
                                        <li
                                            class="{{ route_group_is_active('in_progress_quote_requests') ? 'active' : '' }}">
                                            <a
                                                href="{{ route('vendor.space.form.get_quote.index', ['quote_status' => 'in_progress', 'language' => $defaultLang->code]) }}">
                                                <span class="sub-item">{{ __('In Progress Requests') }}</span>
                                            </a>
                                        </li>
                                        <li
                                            class="{{ route_group_is_active('closed_quote_requests') ? 'active' : '' }}">
                                            <a
                                                href="{{ route('vendor.space.form.get_quote.index', ['quote_status' => 'closed', 'language' => $defaultLang->code]) }}">
                                                <span class="sub-item">{{ __('Closed Requests') }}</span>
                                            </a>
                                        </li>
                                        <li
                                            class="{{ route_group_is_active('cancelled_quote_requests') ? 'active' : '' }}">
                                            <a
                                                href="{{ route('vendor.space.form.get_quote.index', ['quote_status' => 'cancelled', 'language' => $defaultLang->code]) }}">
                                                <span class="sub-item">{{ __('Cancelled Requests') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>


                            <!-- Tour Requests Submenu -->
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
                                                href="{{ route('vendor.space.form.tour_request.index', ['language' => $defaultLang->code]) }}">
                                                <span class="sub-item">{{ __('All Requests') }}</span>
                                            </a>
                                        </li>
                                        <li
                                            class="{{ route_group_is_active('pending_tour_requests') ? 'active' : '' }}">
                                            <a
                                                href="{{ route('vendor.space.form.tour_request.index', ['tour_status' => 'pending', 'language' => $defaultLang->code]) }}">
                                                <span class="sub-item">{{ __('Pending Requests') }}</span>
                                            </a>
                                        </li>
                                        <li
                                            class="{{ route_group_is_active('confirmed_tour_requests') ? 'active' : '' }}">
                                            <a
                                                href="{{ route('vendor.space.form.tour_request.index', ['tour_status' => 'confirmed', 'language' => $defaultLang->code]) }}">
                                                <span class="sub-item">{{ __('Confirmed Requests') }}</span>
                                            </a>
                                        </li>
                                        <li
                                            class="{{ route_group_is_active('closed_tour_requests') ? 'active' : '' }}">
                                            <a
                                                href="{{ route('vendor.space.form.tour_request.index', ['tour_status' => 'closed', 'language' => $defaultLang->code]) }}">
                                                <span class="sub-item">{{ __('Closed Requests') }}</span>
                                            </a>
                                        </li>
                                        <li
                                            class="{{ route_group_is_active('cancelled_tour_requests') ? 'active' : '' }}">
                                            <a
                                                href="{{ route('vendor.space.form.tour_request.index', ['tour_status' => 'cancelled', 'language' => $defaultLang->code]) }}">
                                                <span class="sub-item">{{ __('Cancelled Requests') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <!-- Booking Management Submenu -->
                            <li class="submenu">
                                <a data-toggle="collapse" href="#booking_management"
                                    aria-expanded="{{ route_group_is_active('booking_management') ? 'true' : 'false' }}">
                                    <span class="sub-item">{{ __('Booking Management') }}</span>
                                    <span class="caret"></span>
                                </a>
                                <div id="booking_management"
                                    class="collapse @if (route_group_is_active('booking_management')) show @endif">
                                    <ul class="nav nav-collapse subnav">
                                        @if (!empty($permissions) && in_array('Add Booking', $permissions))
                                            <li class="@if (route_group_is_active('add_booking')) active @endif">
                                                <a
                                                    href="{{ route('vendor.add_booking.space_selection', ['language' => $defaultLang->code]) }}">
                                                    <span class="sub-item">{{ __('Add Booking') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        <li
                                            class="{{ route_group_is_active('all_bookings') && empty(request()->input('booking_status')) ? 'active' : '' }}">
                                            <a
                                                href="{{ route('vendor.booking_record.index', ['language' => $defaultLang->code]) }}">
                                                <span class="sub-item">{{ __('All Bookings') }}</span>
                                            </a>
                                        </li>
                                        <li class="{{ route_group_is_active('pending_bookings') ? 'active' : '' }}">
                                            <a
                                                href="{{ route('vendor.booking_record.index', ['booking_status' => 'pending', 'language' => $defaultLang->code]) }}">
                                                <span class="sub-item">{{ __('Pending Bookings') }}</span>
                                            </a>
                                        </li>
                                        <li class="{{ route_group_is_active('approved_bookings') ? 'active' : '' }}">
                                            <a
                                                href="{{ route('vendor.booking_record.index', ['booking_status' => 'approved', 'language' => $defaultLang->code]) }}">
                                                <span class="sub-item">{{ __('Approved Bookings') }}</span>
                                            </a>
                                        </li>
                                        <li class="{{ route_group_is_active('rejected_bookings') ? 'active' : '' }}">
                                            <a
                                                href="{{ route('vendor.booking_record.index', ['booking_status' => 'rejected', 'language' => $defaultLang->code]) }}">
                                                <span class="sub-item">{{ __('Rejected Bookings') }}</span>
                                            </a>
                                        </li>
                                        <li class="{{ route_group_is_active('booking_report') ? 'active' : '' }}">
                                            <a
                                                href="{{ route('vendor.booking_record.booking_report', ['language' => $defaultLang->code]) }}">
                                                <span class="sub-item">{{ __('Report') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Subscription Management route start --}}

                <li class="nav-item @if (route_group_is_active('subscription')) active @endif">
                    <a data-toggle="collapse" href="#subscription_management">
                        <i class="fal fa-file-invoice-dollar"></i>
                        <p>{{ __('Subscription') }}</p>
                        <span class="caret"></span>
                    </a>
                    <div id="subscription_management" class="collapse @if (route_group_is_active('subscription')) show @endif">
                        <ul class="nav nav-collapse">
                            <li class="{{ route_group_is_active('buy_plan') ? 'active' : '' }}">
                                <a href="{{ route('vendor.plan.extend.index', ['language' => $defaultLang->code]) }}">
                                    <span class="sub-item">{{ __('Buy Plan') }}</span>
                                </a>
                            </li>

                            <li class="{{ route_group_is_active('subscription_log') ? 'active' : '' }}">
                                <a href="{{ route('vendor.subscription_log', ['language' => $defaultLang->code]) }}">
                                    <span class="sub-item">{{ __('Subscription Log') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item {{ route_group_is_active('withdrawals') ? 'active' : '' }}">
                    <a data-toggle="collapse" href="#Withdrawals">
                        <i class="fal fa-donate"></i>
                        <p>{{ __('Withdrawals') }}</p>
                        <span class="caret"></span>
                    </a>
                    <div id="Withdrawals" class="collapse {{ route_group_is_active('withdrawals') ? 'show' : '' }}">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('vendor.withdraw') ? 'active' : '' }}">
                                <a href="{{ route('vendor.withdraw', ['language' => $defaultLang->code]) }}">
                                    <span class="sub-item">{{ __('Withdrawal Requests') }}</span>
                                </a>
                            </li>

                            <li class="{{ request()->routeIs('vendor.withdraw.create') ? 'active' : '' }}">
                                <a href="{{ route('vendor.withdraw.create', ['language' => $defaultLang->code]) }}">
                                    <span class="sub-item">{{ __('Make a Request') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ request()->routeIs('vendor.transaction') ? 'active' : '' }}">
                    <a href="{{ route('vendor.transaction', ['language' => $defaultLang->code]) }}">
                        <i class="fas fa-exchange-alt"></i>
                        <p>{{ __('Transactions') }}</p>
                    </a>
                </li>
                {{-- Support Ticket - --}}
                @if (!empty($permissions) && in_array('Support Tickets', $permissions))
                    <li class="nav-item {{ route_group_is_active('support_ticket') ? 'active' : '' }}">
                        <a data-toggle="collapse" href="#support_ticket">
                            <i class="la flaticon-web-1"></i>
                            <p>{{ __('Support Tickets') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="support_ticket"
                            class="collapse {{ route_group_is_active('support_ticket') ? 'show' : '' }}">
                            <ul class="nav nav-collapse">

                                <li
                                    class="{{ (request()->routeIs('vendor.support_ticket') || request()->routeIs('vendor.support_ticket.message')) && empty(request()->input('status')) ? 'active' : '' }}">
                                    <a
                                        href="{{ route('vendor.support_ticket', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('All Tickets') }}</span>
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('vendor.support_ticket.create') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('vendor.support_ticket.create', ['language' => $defaultLang->code]) }}">
                                        <span class="sub-item">{{ __('Add Ticket') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                <li class="nav-item {{ request()->routeIs('vendor.edit.profile') ? 'active' : '' }}">
                    <a href="{{ route('vendor.edit.profile', ['language' => $defaultLang->code]) }}">
                        <i class="fal fa-user-edit"></i>
                        <p>{{ __('Edit Profile') }}</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('vendor.recipient_mail') ? 'active' : '' }}">
                    <a href="{{ route('vendor.recipient_mail', ['language' => $defaultLang->code]) }}">
                        <i class="fal fa-envelope"></i>
                        <p>{{ __('Recipient Mail') }}</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('vendor.change_password') ? 'active' : '' }}">
                    <a href="{{ route('vendor.change_password', ['language' => $defaultLang->code]) }}">
                        <i class="fal fa-key"></i>
                        <p>{{ __('Change Password') }}</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('vendor.logout') ? 'active' : '' }}">
                    <a href="{{ route('vendor.logout') }}">
                        <i class="fal fa-sign-out"></i>
                        <p>{{ __('Logout') }}</p>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>
