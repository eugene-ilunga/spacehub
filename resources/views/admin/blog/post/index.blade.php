@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Posts') }}</h4>
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
                <a href="#">{{ __('Pages') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Blog') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Posts') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4 col-12 mb-2 mb-lg-0">
                            <div class="card-title d-inline-block">{{ __('All Posts') }}</div>
                        </div>

                        <div class="col-lg-8 col-12">
                            <form id="searchForm" action="{{ route('admin.blog_management.posts') }}" method="GET"
                                class="row">
                                <div class="col-lg-3 col-12 mb-2 mb-lg-0">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="{{ __('Search by title') }}" value="{{ request('name') }}"
                                        onkeypress="if(event.keyCode == 13) 
                                        { event.preventDefault(); 
                                         document.getElementById('searchForm').submit(); }">
                                </div>
                                <div class="col-lg-3 col-12 mb-2 mb-lg-0">
                                    <select name="category" class="form-control" onchange="this.form.submit()">
                                        <option value="">{{ __('All Categories') }}</option>
                                        @foreach ($filterCategories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-12 mb-2 mb-lg-0">
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="">{{ __('All Status') }}</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                            {{ __('Active') }}</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                            {{ __('Deactive') }}</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-12 text-lg-right text-left mt-2 mt-lg-0">
                                    <a href="{{ route('admin.blog_management.create_post', ['language' => $defaultLang->code]) }}"
                                        class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i>
                                        {{ __('Add Post') }}</a>

                                    <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                                        data-href="{{ route('admin.blog_management.bulk_delete_post') }}">
                                        <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($posts) == 0)
                                <h3 class="text-center mt-2">{{ __('NO POST FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ __('Title') }}</th>
                                                <th scope="col">{{ __('Category') }}</th>
                                                <th scope="col">{{ __('Status') }}</th>
                                                <th scope="col">{{ __('Publish Date') }}</th>
                                                <th scope="col">{{ __('Serial Number') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($posts as $post)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $post->id }}">
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('blog.post_details', ['slug' => $post->slug, 'id' => $post->id]) }}"
                                                            target="_blank">

                                                            {{ truncate_text_space_title($post->title, 60) }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $post->categoryName }}</td>
                                                    <td>
                                                        @if ($post->status == 1)
                                                            <span class="badge badge-success">{{ __('Active') }}</span>
                                                        @else
                                                            <span class="badge badge-danger">{{ __('Deactive') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $date = Carbon\Carbon::parse($post->created_at);
                                                        @endphp

                                                        {{ date_format($date, 'M d, Y') }}
                                                    </td>
                                                    <td>{{ $post->serial_number }}</td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm mr-1 mb-1"
                                                            href="{{ route('admin.blog_management.edit_post', ['id' => $post->id, 'language' => $defaultLang->code]) }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>

                                                        </a>

                                                        <form class="deleteForm d-inline-block"
                                                            action="{{ route('admin.blog_management.delete_post', ['id' => $post->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm deleteBtn mb-1">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>

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

                <div class="card-footer">
                    <div class="pl-3 pr-3 text-center">
                        <div class="d-inline-block mx-auto">
                            {{ $posts->appends([
                                    'language' => $defaultLang->code,
                                ])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
