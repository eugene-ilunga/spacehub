@props([
    'tabs' => [
        ['id' => 'tab1', 'label' => __('Overview'), 'active' => true],
        ['id' => 'tab2', 'label' => __('Amenities')],
        ['id' => 'tab3', 'label' => __('Location')],
        ['id' => 'tab4', 'label' => __('Reviews')],
    ],
    'hoverEffect' => 'fancyHover',
])

<div class="tabs-navigation tabs-navigation_v2 mt-40">
    <ul class="nav nav-tabs" data-hover="{{ $hoverEffect }}">
        @foreach ($tabs as $tab)
            <li class="nav-item {{ $tab['active'] ?? false ? 'active' : '' }}">
                <button class="nav-link hover-effect btn-lg {{ $tab['active'] ?? false ? 'active' : '' }}"
                    data-bs-toggle="tab" data-bs-target="#{{ $tab['id'] }}" type="button">
                    {{ $tab['label'] }}
                </button>
            </li>
        @endforeach
    </ul>
</div>
