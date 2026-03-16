@php $bgImage = $image ? asset('assets/img/' . $image) : null; @endphp

@if ($status == 1)
    {{-- home‑1 --}}
    @if ($variant === 'home-1' && empty($bgImage) && empty($videoLink))
        <div class="row">
            <div class="col">
                <h3 class="text-center mt-5">{{ __('No Highlights to Showcase Yet!') }}</h3>
            </div>
        </div>
    @else
        {{-- common HTML --}}
        @switch($variant)
            @case('home-1')
                <div class="video-banner-area video-banner-area_v1 py-4 py-lg-5 bg-img bg-cover z-1"
                    data-bg-img="{{ $bgImage }}" data-aos="fade-up">
                    <div class="overlay opacity-50"></div>
                @break

                @case('home-2')
                    <div class="video-banner-area video-banner-area_v2 bg-img bg-cover z-1" data-bg-img="{{ $bgImage }}"
                        data-aos="fade-up">
                        <div class="overlay"></div>
                    @break

                    @default
                        {{-- home‑3 --}}
                        <div class="video-banner-area py-4 py-lg-5 bg-img bg-cover z-1" data-bg-img="{{ $bgImage }}"
                            data-aos="fade-up">
                            <div class="overlay opacity-50"></div>
                    @endswitch

                    <div class="container">
                        <div
                            class="video-btn-parent bg-none lazy-container
                           {{ $variant === 'home-3' ? 'radius-md border border-white' : '' }}
                           ratio ratio-21-10">
                            <a href="{{ $videoLink}}" class="video-btn video-btn-white p-absolute youtube-popup"
                                title="{{ __('Play Video') }}">
                                <i class="fas fa-play"></i>
                            </a>

                            {{-- home‑1 --}}
                            @if ($variant === 'home-1')
                                <div class="line"><span></span><span></span><span></span><span></span></div>
                            @endif
                        </div>
                    </div>
                </div>
    @endif
@endif
