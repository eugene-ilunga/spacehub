@extends('frontend.layout')
@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->customer_order_page_title ?? __('My Orders') }}
    @else
        {{ __('My Orders') }}
    @endif
@endsection
@php
    $title = $pageHeading->customer_order_page_title ?? __('My Orders');
@endphp

@section('content')
    <!-- Breadcrumb start -->
    @includeIf('frontend.user.breadcrumb', ['breadcrumb' => $breadcrumb ?? '', 'title' => $title ?? ''])
    <!-- Breadcrumb end -->


    <!-- Dashboard-area start-->
    <div class="user-dashboard pt-100 pb-60">
        <div class="container">
            <div class="row gx-xl-5">

                @includeIf('frontend.user.profile.side-navbar')

                <div class="col-lg-9">
                    <div class="account-info radius-md mb-40">
                        <div class="title">
                            <h4>{{ __('Orders') }}</h4>
                        </div>
                        <div class="main-info">
                            <div class="main-table">
                                @if (count($orders) == 0)
                                    <h3 class="text-center mt-3">{{ __('No Order Found') . '!' }}</h3>
                                @else
                                    <div class="table-responsiv">
                                        <table id="myTable" class="table table-striped w-100">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Product Name') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Payment Status') }}</th>
                                                    <th>{{ __('Order Status') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($orders as $order)
                                                    <tr>
                                                        @php
                                                            $product = App\Models\Shop\ProductContent::where(
                                                                'product_id',
                                                                @$order->item->first()->product_id,
                                                            )
                                                                ->where('language_id', $currentLanguageInfo->id)
                                                                ->first();
                                                        @endphp
                                                        <td>
                                                            @if ($product)
                                                                <a href="{{ route('shop.product_details', ['slug' => $product->slug]) }}"
                                                                    target="_blank">
                                                                    {{ strlen($product->title) > 20 ? mb_substr($product->title, 0, 20, 'UTF-8') . '...' : $product->title }}
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($order->created_at)->isoFormat('Do MMMM YYYY') }}
                                                        </td>
                                                        @php
                                                            if ($order->payment_status == 'pending') {
                                                                $payment_bg = 'bg-waring';
                                                            } elseif ($order->payment_status == 'completed') {
                                                                $payment_bg = 'bg-success';
                                                            } elseif ($order->payment_status == 'rejected') {
                                                                $payment_bg = 'bg-danger';
                                                            }

                                                            if ($order->order_status == 'pending') {
                                                                $order_bg = 'bg-warning';
                                                            } elseif ($order->order_status == 'processing') {
                                                                $order_bg = 'bg-info';
                                                            } elseif ($order->order_status == 'completed') {
                                                                $order_bg = 'bg-success';
                                                            } elseif ($order->order_status == 'rejected') {
                                                                $order_bg = 'bg-danger';
                                                            }
                                                        @endphp
                                                        <td><span
                                                                class="badge {{ $payment_bg }}">{{ __(ucfirst($order->payment_status)) }}</span>
                                                        </td>
                                                        <td><span
                                                                class="badge {{ $order_bg }}">{{ __(ucfirst($order->order_status)) }}</span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('user.order.details', $order->id) }}"
                                                                class="btn"><i class="fas fa-eye"></i>
                                                                {{ __('Details') }}</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard-area end -->
@endsection
