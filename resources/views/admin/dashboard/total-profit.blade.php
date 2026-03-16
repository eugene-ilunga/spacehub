@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Monthly Total Profit') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard', ['language' => $defaultLang->code]) }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Monthly Total Profit') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">

                        <div class="col-lg-5">
                            <div class="card-title d-inline-block">
                                {{ __('Monthly Total Profit') }}
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="card-title d-inline-block">
                                <form action="{{ route('admin.dashboard.monthly_profit') }}" id="year" method="get">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <select id="year" class="form-control" name="year"
                                                    onchange="document.getElementById('year').submit()">
                                                    <option value="">{{ __('Select Year') }}</option>
                                                    @for ($year = 2023; $year <= date('Y'); $year++)
                                                        <option
                                                            @if (request()->input('year') == '' && $year == date('Y')) {{ 'selected' }}
                                                                @elseif(request()->input('year') == $year)
                                                                    {{ 'selected' }} @endif
                                                            value="{{ $year }}">
                                                            {{ $year }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="table-responsive">
                            <table class="table table-striped mt-3" id="">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('Month Name') }}</th>
                                        <th scope="col">{{ __('Total Profit') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($months as $key => $monthName)
                                        @php
                                            // Try to parse month abbreviation
                                            $dateObj = DateTime::createFromFormat('!M', $monthName);

                                            // If parsing fails (false), try using numeric month value
                                            if (!$dateObj && is_numeric($monthName)) {
                                                $dateObj = DateTime::createFromFormat('!m', $monthName);
                                            }

                                            // Fallback to just printing the raw value if parsing fails completely
                                            $formattedMonth = $dateObj ? $dateObj->format('F') : $monthName;
                                        @endphp
                                        <tr>
                                              <td>{{ __($formattedMonth) }}</td>

                                            <td class="ltr">
                                                {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                                                {{ round($totalProfits[$key], 2) }}
                                                {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer"></div>
        </div>
    </div>
    </div>
@endsection
