@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Holidays') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
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
        <a href="#">{{ __('Holidays') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row justify-content-between">
            <div class="col-md-4 col-lg-4">
              <div class="card-title d-inline-block">{{ __('Holidays') }}</div>
            </div>
            <div class="col-md-4 col-lg-4 mt-3 mt-lg-0">
              <div class="d-flex justify-content-md-end gap-10">
                <a class="btn btn-info btn-sm d-inline-block" href="#" data-toggle="modal"
                  data-target="#createModal">
                  <span class="btn-label">
                    <i class="fas fa-plus"></i>
                  </span>
                  {{ __('Add Holiday') }}
                </a>
                <a class="btn btn-info btn-sm float-right d-inline-block"
                    href="{{ route('admin.holiday.select_vendor') }}?language={{ $defaultLang->code }}">
                    <span class="btn-label">
                        <i class="fas fa-backward mdb_12"></i>
                    </span>
                    {{ __('Back') }}
                </a>
                <button class="btn btn-danger btn-sm d-none bulk-delete"
                  data-href="{{ route('admin.space.holiday.bluk_destroy') }}">
                  <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                </button>
                
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($globalHoliday) == 0)
                <h3 class="text-center mt-2">{{ __('NO HOLIDAY FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Date') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($globalHoliday as $holiday)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $holiday->id }}">
                          </td>
                          <td>{{ $holiday->date }}</td>
                          <td>
                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.space.holiday.delete', ['id' => $holiday->id]) }}" method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                                {{ __('Delete') }}
                              </button>
                            </form>
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
  @include('admin.holiday.create')
@endsection
