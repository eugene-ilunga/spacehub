<!-- Footer-area start -->
@if ($isActiveSection->footer_section_status == 1)
    <footer class="footer-area footer-area_v1 bg-img bg-cover border-top"
        data-bg-img="{{ asset('assets/img/' . $basicInfo->footer_section_bg_img) }}">
        <div class=""></div>
        <div class="footer-top pt-100 pb-70">
            <div class="container">
                <div class="row gx-xl-5 justify-content-between">
                    <div class="col-xl-3 col-lg-5 col-md-6">
                        <div class="footer-widget" data-aos="fade-up">
                            <!-- Logo -->
                            <div class="logo mb-20">
                                @if (!empty($basicInfo->footer_logo))
                                    <a class="navbar-brand" href="{{ route('index') }}" target="_self"
                                        title="{{ __('Link') }}">
                                        <img class="lazyload" src="{{ asset('assets/img/' . $basicInfo->footer_logo) }}"
                                            data-src="{{ asset('assets/img/' . $basicInfo->footer_logo) }}"
                                            alt="{{ __('Brand Logo') }}">
                                    </a>
                                @endif
                            </div>
                            @if (!empty($footerInfo))
                                <p>
                                    {{ $footerInfo->about_company }}
                                </p>
                            @else
                                <p>
                                    {{ __('Footer content coming soon! Stay tuned for updates.') }}
                                </p>
                            @endif
                            <!-- social-link -->
                            @if (count($socialMediaInfos) > 0)
                                <div class="social-link rounded justify-content-start mb-10">

                                    @foreach ($socialMediaInfos as $socialMediaInfo)
                                        <a href="{{ $socialMediaInfo->url }}" target="_blank" title="Link"><i
                                                class="{{ $socialMediaInfo->icon }}"></i></a>
                                    @endforeach

                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-sm-6">
                        <div class="footer-widget" data-aos="fade-up">
                            <h4 class="title">{{ __('Useful Links') }}</h4>
                            @if (count($quickLinkInfos) > 0)
                                <ul class="footer-links">
                                    @foreach ($quickLinkInfos as $quickLinkInfo)
                                        <li>
                                            <a href="{{ $quickLinkInfo->url }}" target="_self"
                                                title="{{ $quickLinkInfo->title }}">{{ $quickLinkInfo->title }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>{{ __('Thank you for visiting! Useful links will be added shortly.') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-sm-6">
                        <div class="footer-widget" data-aos="fade-up">

                            <h4 class="title">{{ __('Contact Info') }}</h4>

                            <ul class="footer-links">
                                @if (!empty($contactAddress))
                                    <li>
                                        <p>
                                            <i class="fas fa-map-marker-alt"></i>{{ $contactAddress }}
                                        </p>
                                    </li>
                                @endif
                                @foreach ($contactMobiles as $mobile)
                                    <li>
                                        <a href="tel:{{ $mobile }}">
                                            <i class="fas fa-headphones"></i>{{ $mobile }}
                                        </a>
                                    </li>
                                @endforeach
                                @foreach ($contactEmails as $email)
                                    <li>
                                        <a href="mailto:{{ $email }}">
                                            <i class="fas fa-envelope"></i>{{ $email }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="footer-widget" data-aos="fade-up">
                            <h4 class="title">{{ __('Subscribe Us') }}</h4>
                            @if (!empty($footerInfo))
                                <p>
                                    {{ $footerInfo->newsletter_text }}
                                </p>
                            @endif
                            <form id="newsletterForm" action="{{ route('store.subscriber') }}"
                                class="subscription-form" method="POST">
                                @csrf
                                <div class="input-inline p-1 bg-white shadow-md radius-sm">
                                    <input class="form-control border-0"
                                        placeholder="{{ __('Email Address') }}" type="text"
                                        name="email_id" required>
                                    <button class="btn btn-md btn-primary radius-sm" type="submit"
                                        aria-label="button">{{ __('Subscribe') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copy-right-area py-4">
            <div class="container">
                <div class="copy-right-content">

                    @if (!empty($footerInfo))
                        <span>
                            {{ $footerInfo->copyright_text }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </footer>

@endif

<!-- Footer-area end-->
