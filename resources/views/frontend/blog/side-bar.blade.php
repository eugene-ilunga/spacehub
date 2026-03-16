@if (request()->routeIs('blog'))
    <div class="widget widget-search py-25">
        <h5 class="title mb-15">{{ __('Search Posts') }}</h5>
        <div class="search-form">
            <form id="searchForm" action="{{ route('blog') }}" method="GET">
                <div class="input-inline bg-white shadow-md rounded-pill">
                    <input class="form-control border-0" placeholder="{{ __('Search By Title') }}" type="text"
                        name="title" value="{{ !empty(request()->input('title')) ? request()->input('title') : '' }}"
                        >
                    @if (!empty(request()->input('category')))
                        <input type="hidden" name="category" value="{{ request()->input('category') }}">
                    @endif

                    <button class="btn-icon rounded-pill search-btn " type="submit" aria-label="Search button">
                        <i class="far fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

<div class="widget widget-blog-categories py-25">
    <h5 class="title">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#blogCategory">
            {{ __('Categories') }}
        </button>
    </h5>
    @if (!empty($categories))
        <div id="blogCategory" class="collapse show">
            <div class="accordion-body mt-20 scroll-y">
                <ul class="list-unstyled m-0">
                    @foreach ($categories as $category)
                        <li class="d-flex align-items-center justify-content-between">
                            <a href="{{ route('blog', ['category' => $category->slug]) }}" target="_self" title="Blogs"
                                class="blog-category @if ($category->slug == request()->input('category')) active @endif"
                                data-category_slug="{{ $category->slug }}">
                                <i class="fal fa-folder"></i>
                                {{ @$category->name }}</a>
                            <span class="tqy">({{ @$category->postCount }})</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @else
        <h4>{{ __('No Categories Available Yet!') }}</h4>
        <p>{{ __('Check back soon for updates.') }}</p>
    @endif
</div>
