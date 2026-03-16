<div class="col-lg-4">
    <aside class="widget-area" data-aos="fade-up">
        <div class="widget-vendor mb-40 border radius-md p-20">
            <div class="vendor mb-20 text-center">
                @if (request()->input('admin') != true)
                    <figure class="vendor-img mx-auto mb-15">
                        <div class="lazy-container radius-md ratio ratio-1-1">
                            @if (!is_null($seller->photo))
                                <img class="lazyload" src="{{ asset('assets/admin/img/seller-photo/' . $seller->photo) }}"
                                    data-src="{{ asset('assets/admin/img/seller-photo/' . $seller->photo) }}"
                                    alt="{{ __('image') }}">
                            @else
                                <img class="lazyload" src="{{ asset('assets/frontend/images/vendor/vendor.png') }}"
                                    data-src="{{ asset('assets/frontend/images/vendor/vendor.png') }}" alt="image">
                            @endif
                        </div>
                    </figure>
                    <div class="vendor-info">
                        <h5>{{ __('Overview Of') . ' ' }} {{ $seller->username }}</h5>
                        <div class="card_text click-show">
                            <div class="show-content">
                                <p>{!! @$sellerInfo->details !!}</p>
                            </div>
                            <div class="read-more-btn">
                                <span>{{ __('Read more') }}</span>
                                <span>{{ __('Read less') }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    @php
                        $admin = \App\Models\Admin::first();
                    @endphp
                    <figure class="vendor-img mx-auto mb-15">
                        <div class="lazy-container radius-md ratio ratio-1-1">
                            @if (!is_null($admin->image))
                                <img class="lazyload" src="{{ asset('assets/img/admins/' . $admin->image) }}"
                                    data-src="{{ asset('assets/img/admins/' . $admin->image) }}" alt="image">
                            @else
                                <img class="lazyload" src="{{ asset('assets/frontend/images/vendor/vendor.png') }}"
                                    data-src="{{ asset('assets/frontend/images/vendor/vendor.png') }}" alt="image">
                            @endif
                        </div>
                    </figure>
                    <div class="vendor-info">
                        <h5>{{ __('Overview Of') . ' ' }} {{ $admin->username }}</h5>
                    </div>

                @endif
            </div>
            <hr>
            <!-- Toggle list start -->
            <ul class="toggle-list list-unstyled mt-25" data-toggle-list="vendorToggle" data-toggle-show="5">
                <li>
                    <span class="first">{{ __('Total Spaces') . ':' }}</span>
                    <span class="last">{{ count($totalSpaces) }} </span>
                </li>
                <li>
                    <span class="first">{{ __('Total Bookings') . ':' }}</span>
                    <span class="last">{{ $numberOfBooking }}</span>
                </li>
                @php
                    $Space = \App\Models\Space::select('id', 'created_at')
                        ->where([['spaces.space_status', '=', 1], ['spaces.seller_id', $seller->id]])
                        ->oldest()
                        ->first();

                @endphp
                @if (!is_null($Space) && !is_null($Space->created_at))
                    <li>
                        <span class="first">{{ __('First Space Added') . ':' }}</span>
                        <span class="last">{{ $Space->created_at->format('jS F Y') }}</span>
                    </li>
                @endif

                @if (request()->input('admin') != true)
                    @if (@$seller->show_email_addresss == 1)
                        <li>
                            <span class="first">{{ __('Email') . ':' }}</span>
                            <span class="last"><a
                                    href="mailto:{{ $seller->email }}">{{ @$seller->email }}</a></span>
                        </li>
                    @endif
                    @if (@$seller->show_phone_number == 1)
                        @if (!empty($seller->phone))
                            <li>
                                <span class="first">{{ __('Phone') . ':' }}</span>
                                <span class="last"><a
                                        href="tel:{{ $seller->phone }}">{{ @$seller->phone }}</a></span>
                            </li>
                        @endif
                    @endif
                    @if (
                        !empty($sellerInfo) &&
                            (!is_null($sellerInfo->country) ||
                                !is_null($sellerInfo->address) ||
                                !is_null($sellerInfo->city) ||
                                !is_null($sellerInfo->state)))
                        <li>
                            <span class="first">{{ __('Location') . ':' }}</span>
                            <span class="last">
                                @if (!is_null($sellerInfo->address))
                                    {{ $sellerInfo->address }},
                                @endif
                                @if (!is_null($sellerInfo->city))
                                     {{ $sellerInfo->city }},
                                @endif
                                @if (!is_null($sellerInfo->state))
                                     {{ $sellerInfo->state }},
                                @endif
                                @if (!is_null($sellerInfo->country))
                                    {{ $sellerInfo->country }}
                                @endif
                            </span>
                        </li>
                    @endif
                    <li>
                        <span class="first">{{ __('Member Since') . ':' }}</span>
                        <span
                            class="last font-sm">{{ \Carbon\Carbon::parse($seller->created_at)->format('dS M Y') }}</span>
                    </li>
                    <li>
                        <span class="first">{{ __('Verified User') . ':' }}</span>
                        <span class="last font-sm">
                            @if ($seller->email_verified_at !== null || $seller->username == 'admin')
                                {{ __('Yes') }}
                            @else
                                {{ __('No') }}
                            @endif
                        </span>
                    </li>
                @endif
            </ul>
            @php
                $showMoreText = __('Show More') . ' +';
                $showLessText = __('Show Less') . ' -';
            @endphp
            <span class="show-more-btn mt-15" data-show-more="{{ $showMoreText }}"
                data-show-less="{{ $showLessText }}" data-toggle-btn="toggleListBtn">
                {{ __('Show More') . '+' }}
            </span>
              @if($seller->show_contact_form == 1)
            <hr>
            <!-- Toggle list end -->
            <div class="cta-btn mt-20">
                <button class="btn btn-lg btn-primary radius-sm w-100" data-bs-toggle="modal"
                    data-bs-target="#contactModal" type="button" aria-label="button">{{ __('Contact Now') }}</button>
            </div>
             @endif
        </div>
    </aside>
</div>
