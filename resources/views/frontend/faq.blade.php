@extends('frontend.layout')

@php
    $title = $pageHeading->faq_page_title ?? __('FAQ');
@endphp

{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />

@section('content')
    <!-- Breadcrumb start -->
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])
    <!-- Breadcrumb end -->

    <!-- Faq-area start -->
    <div class="faq-area pt-100 pb-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9" data-aos="fade-up">
                    @if (count($faqs) > 0)
                        <div class="accordion pb-10" id="faqAccordion">
                            @foreach ($faqs as $key => $faq)
                                <div class="accordion-item border radius-md mb-30">
                                    <h6 class="accordion-header" id="heading{{ $key }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $key }}"
                                            aria-controls="collapse{{ $key }}">
                                            {{ $key + 1 }}. {{ $faq['question'] }}
                                        </button>
                                    </h6>
                                    <div id="collapse{{ $key }}" class="accordion-collapse collapse"
                                        aria-labelledby="heading{{ $key }}" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <p>
                                                {{ $faq['answer'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <h3>{{ __("We haven't received any questions yet. Check back later!") }}</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Faq-area end -->
    @if (!empty(showAd(3)))
        <div class="text-center mb-4">
            {!! showAd(3) !!}
        </div>
    @endif

@endsection
