@extends('frontend.layout')
@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->customer_wishlist_page_title ?? __('My Wishlists') }}
    @else
        {{ __('My Wishlists') }}
    @endif
@endsection
@php
    $title = $pageHeading->customer_booking_details_page_title ?? __('My Wishlists');
@endphp

@section('content')
    @includeIf('frontend.user.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

    <!--====== Start Service Wishlist Section ======-->
    <section class="user-dashboard pt-100 pb-60">
        <div class="container">
            <div class="row">
                @includeIf('frontend.user.profile.side-navbar')

                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="user-profile-details mb-40">
                                <div class="account-info">
                                    <div class="title">
                                        <h4>{{ __('Title') }}</h4>
                                    </div>

                                    <div class="main-info">
                                        @if (count($listedServices) == 0)
                                            <div class="row text-center mt-2">
                                                <div class="col">
                                                    <h4>{{ __('No Space Found!') }}</h4>
                                                </div>
                                            </div>
                                        @else
                                            <div class="main-table">
                                                <div class="table-responsive">
                                                    <table id="user-datatable" class="table table-striped w-100">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Spaces') }}</th>
                                                                <th>{{ __('Action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($listedServices as $listedService)
                                                                <tr id="space-{{ $listedService->space_id }}">
                                                                    @php
                                                                        $serviceTitle =
                                                                            $listedService->serviceContent->title;
                                                                        $slug = $listedService->serviceContent->slug;
                                                                        $serviceId = $listedService->space_id;
                                                                    @endphp

                                                                    <td class="ps-3">
                                                                        <a class="text-primary"
                                                                            href="{{ route('space.details', ['slug' => $slug, 'id' => $serviceId]) }}"
                                                                            target="_blank">
                                                                            {{ strlen($serviceTitle) > 60 ? mb_substr($serviceTitle, 0, 60, 'UTF-8') . '...' : $serviceTitle }}
                                                                        </a>
                                                                    </td>
                                                                    <td class="ps-3">
                                                                        <a href="{{ route('space.details', ['slug' => $slug, 'id' => $serviceId]) }}"
                                                                            class="btn btn-sm btn-primary rounded-1 {{ $currentLanguageInfo->direction == 1 ? 'ms-1' : 'me-1' }}"
                                                                            target="_blank">
                                                                            {{ __('Details') }}
                                                                        </a>

                                                                        <form
                                                                            action="{{ route('user.space_wishlist.remove', ['space_id' => $serviceId]) }}"
                                                                            method="POST" class="d-inline">
                                                                            @csrf
                                                                            <button type="submit"
                                                                                class="btn btn-sm btn-primary rounded-1">
                                                                                {{ __('Remove') }}
                                                                            </button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End Service Wishlist Section ======-->
@endsection
