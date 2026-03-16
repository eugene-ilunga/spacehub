<div class="col-lg-3">
    <aside class="widget-area mb-40">
        <div class="widget radius-md">
            <ul class="links">
                <li><a href="{{ route('user.dashboard') }}"
                        class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">{{ __('Dashboard') }}</a>
                </li>
                <li><a href="{{ route('user.space_bookings') }}"
                        class="{{ request()->routeIs('user.space_bookings') || request()->routeIs('frontend.user.space-booking-details') ? 'active' : '' }}">{{ __('Space Bookings') }}
                    </a></li>
                <li><a href="{{ route('user.space_wishlist') }}"
                        class="{{ request()->routeIs('user.space_wishlist') ? 'active' : '' }}">{{ __('Space Wishlists') }}
                    </a></li>
                @if ($basicInfo->shop_status == 1)
                    <li><a href="{{ route('user.order.index') }}"
                            class="{{ request()->routeIs('user.order.index') || request()->routeIs('user.order.details') ? 'active' : '' }}">{{ __('Product Orders') }}
                        </a></li>
                @endif
                <li><a href="{{ route('user.edit_profile') }}"
                        class="{{ request()->routeIs('user.edit_profile') ? 'active' : '' }}">{{ __('Edit Profile') }}
                    </a></li>

                @if (!is_null(Auth::guard('web')->user()->password))
                    <li><a href="{{ route('user.change_password') }}"
                            class="{{ request()->routeIs('user.change_password') ? 'active' : '' }}">{{ __('Change Password') }}
                        </a></li>
                @endif
                <li>
                    <a href="{{ route('user.logout') }}">
                        {{ __('Logout') }}
                    </a>
                </li>
            </ul>
        </div>
    </aside>
</div>
