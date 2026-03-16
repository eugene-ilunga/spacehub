
@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Breadcrumb') }}</h4>
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
        <a href="#">{{ __('Pages') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Breadcrumb') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ __('Update Breadcrumb') }}</div>
        </div>
        <div class="card-body pt-5 pb-4">
          <div class="row">
            <div class="col-lg-6 m-auto">
              <form enctype="multipart/form-data" action="{{ route('admin.breadcrumb.update') }}" method="POST">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <div class="col-12 mb-2 pl-0 pr-0">
                        <label for="image"><strong>{{ __('Breadcrumb') }} <span
                              class="text-danger">**</span></strong></label>
                      </div>
                      <div class="col-md-12 thumb-preview mb-3 pl-0 pr-0">
                        <img
                          src="{{ $abs->breadcrumb ? asset('assets/img/' . $abs->breadcrumb) : asset('assets/img/noimage.jpg') }}"
                          alt="..." class="uploaded-img">
                      </div>
                      <br>
                      <div role="button" class="btn btn-primary btn-sm upload-btn" >
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="breadcrumb">
                      </div>
                        @if ($errors->has('breadcrumb'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('breadcrumb') }}</p>
                        @endif
                        <p class="text-warning mt-2 mb-0">
                            {{ __('Image Size') . ': ' . ' ' }} <span dir="ltr">{{ '1920X450 px'  }}</span></p>
                    </div>
                  </div>
                </div>

                <div class="card-footer">
                  <div class="form">
                    <div class="form-group from-show-notify row">
                      <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

