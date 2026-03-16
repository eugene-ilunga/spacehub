@extends('frontend.layout')
@section('style')
<link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-content.css') }}">
@endsection

	{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$pageInfo" />
@php
    $title = $pageInfo->title ?? __('No Page Title Found');
@endphp
@section('content')
<!-- Breadcrumb start -->
@includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])
<!-- Breadcrumb end -->

    <!--====== PAGE CONTENT PART START ======-->
    <section class="custom-page-area ptb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="summernote-content">
                        {!! replaceBaseUrl($pageInfo->content, 'summernote') !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== PAGE CONTENT PART END ======-->
@endsection
