@props(['description' => ''])

<div class="tab-pane slide show active" id="tab1">
    <div class="product-desc mb-40">
        <h4 class="title mb-20">{{ __('Description') }}</h4>
        <div class="summernote-content">{!! $description !!}</div>
    </div>
</div>
