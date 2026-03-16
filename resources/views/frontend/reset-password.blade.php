@extends('frontend.layout')

@section('pageHeading')
  {{ __('Reset Password') }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => __('Reset Password')])

  <!--====== Reset Password Part Start ======-->
  <div class="user-area-section ptb-100 bg-light">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">

          <div class="main-form">

            <div class="user-form">
              <h3 class="title mb-20 text-center">Reset Password</h3>
              <form action="{{ route('user.reset_password_submit') }}" method="POST">
                @csrf
                <div class="form-group mb-4">
                  <label class="form-label font-sm">{{ __('New Password') . '*' }}</label>
                  <input type="password" class="form-control" name="new_password">
                  @error('new_password')
                    <p class="text-danger mt-1">{{ $message }}</p>
                  @enderror
                </div>
  
                <div class="form-group mb-4">
                  <label class="form-label font-sm">{{ __('Confirm New Password') . '*' }}</label>
                  <input type="password" class="form-control" name="new_password_confirmation">
                  @error('new_password_confirmation')
                    <p class="text-danger mt-1">{{ $message }}</p>
                  @enderror
                </div>
  
                <div class="form-group">
                  <button type="submit" class="btn btn-lg btn-primary radius-sm">{{ __('Submit') }}</button>
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <!--====== Reset Password Part End ======-->
@endsection
