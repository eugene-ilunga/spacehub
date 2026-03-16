@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Spaces') }}</h4>
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
                <a href="#">{{ __('Space Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Spaces') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $spaceContent->title ?? '' }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Days') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __($day->name) ?? '' }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                {{ __('Time Slots') }}
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col-lg-6">
                            <div class="card-title d-inline-block">{{ __('Time Slots') }}</div>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex gap-10 justify-content-lg-end mt-2 mt-lg flex-wrap">
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#createModal"
                                    class="btn btn-primary btn-sm float-lg-right float-left ml-2"><i
                                        class="fas fa-plus"></i>
                                    {{ __('Add Time Slot') }}</a>

                                <button class="btn btn-danger btn-sm float-right d-none bulk-delete"
                                    data-href="{{ route('admin.manage_schedule.time_slot.bulk_destroy') }}
                                          ">
                                    <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                                </button>
                                <a class="btn btn-info btn-sm float-right d-inline-block"
                                    href="{{ route('admin.manage_schedule.time_slot.index', ['language' => $defaultLang->code]) }}&space_id={{ $space_id }}">
                                    <span class="btn-label">
                                        <i class="fas fa-backward" style="font-size: 12px;"></i>
                                    </span>
                                    {{ __('Back') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-lg-12">
                        @if (count($timeSlots) == 0)
                            <h3 class="text-center mt-2">{{ __('NO TIME SCHEDULE FOUND') . '!' }}</h3>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped mt-3" id="basic-datatables">
                                    <thead>
                                        <tr>
                                            <th scope="col">
                                                <input type="checkbox" class="bulk-check" data-val="all">
                                            </th>
                                            <th scope="col">{{ __('Day') }}</th>
                                            <th scope="col">{{ __('Start Time') }}</th>
                                            <th scope="col">{{ __('End Time') }}</th>
                                            <th scope="col">{{ __('Rent') }} 
                                                ({{ $settings->base_currency_text }})
                                            </th>
                                            <th scope="col">{{ __('Quantity') }}</th>
                                            <th scope="col">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($timeSlots as $timeSlot)
                                        @php

                                            $startTime = \Carbon\Carbon::parse($timeSlot->start_time, 'UTC')
                                                ->setTimezone($time_zone)
                                                ->format($time_format);

                                            $endTime = \Carbon\Carbon::parse($timeSlot->end_time, 'UTC')
                                                ->setTimezone($time_zone)
                                                ->format($time_format);
                                                
                                        @endphp
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="bulk-check"
                                                        data-val="{{ $timeSlot->id ?? '' }}">
                                                </td>
                                                <td>{{ __(@$timeSlot->name) }}</td>
                                                <td class="ltr">
                                                    {{$startTime }}
                                                    
                                                </td>
                                                <td class="ltr">
                                                    {{ $endTime }}
                                                </td>
                                                <td>{{ $timeSlot->time_slot_rent != null ? $timeSlot->time_slot_rent : '--' }}</td>
                                                <td>{{ $timeSlot->number_of_booking ?? '--' }}</td>
                                                <td>
                                                    <div>
                                                        <a class="btn btn-secondary btn-sm mr-1 editBtn" href="#"
                                                            data-toggle="modal" data-target="#editModal"
                                                            data-id="{{ $timeSlot->id }}"
                                                            data-number_of_booking="{{ $timeSlot->number_of_booking }}"
                                                            data-time_slot_rent="{{ $timeSlot->time_slot_rent }}"
                                                            data-start_time="{{$startTime }}"
                                                            data-end_time="{{$endTime}}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ __('Edit') }}
                                                        </a>
                                                        <form class="deleteForm d-inline-block"
                                                            action="{{ route('admin.manage_schedule.time_slot.delete') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" name="time_slot_id"
                                                                value="{{ $timeSlot->id }}">
                                                            <input type="hidden" name="space_id"
                                                                value="{{ request()->space_id }}">
                                                            <button type="submit"
                                                                class=" btn-danger btn  btn-sm deleteBtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                                @include('admin.manage-schedule.time-slot.edit')
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card-footer">
                    <div class="pl-3 pr-3 text-center">
                        <div class="d-inline-block mx-auto">
                            {{ $timeSlots->appends([
                                    'language' => $defaultLang->code,
                                    'space_id' => $space_id,
                                    'day_id' => $day_id,
                                ])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('admin.manage-schedule.time-slot.create')
@endsection
