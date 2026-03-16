@php
  $seller_id = \Illuminate\Support\Facades\Auth::guard('seller')->user()->id;
  $currentPackage = \App\Http\Helpers\SellerPermissionHelper::currentPackagePermission($seller_id);
  $remainingSpace = \App\Http\Helpers\SellerPermissionHelper::spaceCount($seller_id);
  $totalService = \App\Http\Helpers\SellerPermissionHelper::serviceCount($seller_id);
  $totalSliderImage = \App\Http\Helpers\SellerPermissionHelper::sliderImageCount($seller_id);
    $totalOptions = \App\Http\Helpers\SellerPermissionHelper::optionCount($seller_id);
    $remainingAmenities = \App\Http\Helpers\SellerPermissionHelper::amenitiesCount($seller_id);
@endphp
<div class="main-header">
  <!-- Logo Header Start -->
  <div class="logo-header"
    data-background-color="{{ Session::get('seller_theme_version') == 'light' ? 'white' : 'dark2' }}">
    @if (!empty($websiteInfo->logo))
      <a href="{{ route('index') }}" class="logo" target="_blank">
        <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="logo" class="navbar-brand" width="120">
      </a>
    @endif

    <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon">
        <i class="icon-menu"></i>
      </span>
    </button>
    <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>

    <div class="nav-toggle">
      <button class="btn btn-toggle toggle-sidebar">
        <i class="icon-menu"></i>
      </button>
    </div>
  </div>
  <!-- Logo Header End -->

  <!-- Navbar Header Start -->
  <nav class="navbar navbar-header navbar-expand-lg"
    data-background-color="{{ Session::get('seller_theme_version') == 'light' ? 'white2' : 'dark' }}">
    <div class="container-fluid">
      <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
        <li>
                    @if (Session::has('vendor_lang'))
                        @php
                            $admin_lang = Session::get('vendor_lang');
                            $cd = str_replace('admin_', '', $admin_lang);
                            $sessionLang = \App\Models\Language::where('code', $cd)->first();

                        @endphp
                    @else
                        @php
                            $sessionLang = \App\Models\Language::where('is_default', 1)->first();
                        @endphp
                    @endif
                    @if (!empty($langs))
                        <select name="language" class="form-control langBtn mr-2">
                            <option value="" selected disabled>{{ __('Select a Language') }}</option>
                            @foreach ($langs as $lang)
                                <option value="{{ $lang->code }}"
                                    {{  $lang->code == $sessionLang->code ? 'selected' : '' }}>
                                    {{ __($lang->name) }}</option>
                            @endforeach
                        </select>
                    @endif
                </li>
                <input type="hidden" id="setLocale" value="{{ route('set-Locale-vendor') }}">

        @if(!is_null($currentPackage)  )
          <li>
            <a href="#" class="btn  btn-secondary ml-2 mr-2  btn-sm btn-round" data-toggle="modal"
               data-target="#packageLimitModal">
              @if($remainingSpace == 'downgraded' || count($remainingAmenities) > 0 || count($totalSliderImage) > 0 || count($totalService) > 0 || count($totalOptions) > 0)
                <i class="fas fa-exclamation-triangle text-danger"></i>
              @endif
              {{ __('Check Limit') }}
            </a>

          </li>
        @endif

        <form action="{{ route('vendor.change_theme') }}" class="form-inline mr-3" method="POST">

          @csrf
          <div class="form-group">
            <div class="selectgroup selectgroup-secondary selectgroup-pills">
              <label class="selectgroup-item">
                <input type="radio" name="seller_theme_version" value="light" class="selectgroup-input"
                  {{ Session::get('seller_theme_version') == 'light' ? 'checked' : '' }} onchange="this.form.submit()">
                <span class="selectgroup-button selectgroup-button-icon"><i class="fa fa-sun"></i></span>
              </label>

              <label class="selectgroup-item">
                <input type="radio" name="seller_theme_version" value="dark" class="selectgroup-input"
                  {{ Session::get('seller_theme_version') == 'dark' || !Session::has('seller_theme_version') ? 'checked' : '' }}
                  onchange="this.form.submit()">
                <span class="selectgroup-button selectgroup-button-icon"><i class="fa fa-moon"></i></span>
              </label>
            </div>
          </div>
        </form>

        <li class="nav-item dropdown hidden-caret">
          <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
            <div class="avatar-sm">
              @if (Auth::guard('seller')->user()->photo != null)
                <img src="{{ asset('assets/admin/img/seller-photo/' . Auth::guard('seller')->user()->photo) }}"
                  alt="seller Image" class="avatar-img rounded-circle">
              @else
                <img src="{{ asset('assets/frontend/images/vendor/vendor.png') }}" alt=""
                  class="avatar-img rounded-circle">
              @endif
            </div>
          </a>

          <ul class="dropdown-menu dropdown-user animated fadeIn ">
            <div class="dropdown-user-scroll scrollbar-outer">
              <li class="">
                <div class="user-box">
                  <div class="avatar-lg">
                    @if (Auth::guard('seller')->user()->photo != null)
                      <img src="{{ asset('assets/admin/img/seller-photo/' . Auth::guard('seller')->user()->photo) }}"
                        alt="seller Image" class="avatar-img rounded-circle">
                    @else
                      <img src="{{ asset('assets/frontend/images/vendor/vendor.png') }}" alt=""
                        class="avatar-img rounded-circle">
                    @endif
                  </div>

                  <div class="u-text">
                    <h4>
                      {{ Auth::guard('seller')->user()->username }}
                    </h4>
                    <p class="text-muted">{{ Auth::guard('seller')->user()->email }}</p>
                  </div>
                </div>
              </li>

              <li>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('vendor.edit.profile') }}">
                  {{ __('Edit Profile') }}
                </a>

                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('vendor.change_password') }}">
                  {{ __('Change Password') }}
                </a>

                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('vendor.logout') }}">
                  {{ __('Logout') }}
                </a>
              </li>
            </div>
          </ul>

        </li>

      </ul>
    </div>

  </nav>

  <!-- Navbar Header End -->

</div>




