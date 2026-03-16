
<div class="breadcrumb-area bg-img bg-cover z-1 header-next"
@if (!empty($breadcrumb)) data-bg-img="{{ asset('./assets/img/'.$breadcrumb) }}" @endif
>
  <div class="overlay opacity-75"></div>
  <div class="container">
    <div class="content text-center">
      <h2 class="color-white">{{ !empty($title) ? $title : '' }}</h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">{{__('Dashboard')}}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ !empty($title) ? $title : '' }}</li>
        </ol>
      </nav>
    </div>
  </div>
</div>

