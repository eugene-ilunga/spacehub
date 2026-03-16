           <form action="{{ route('admin.shop_management.products') }}" method="GET">
                <div class="form-row">
                    <!-- Title Search -->
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="input-group">
                            <input name="title" type="text" class="form-control border-right-0"
                                placeholder="{{ __('Search by title') . '...' }}" 
                                value="{{ request('title') }}"
                                aria-label="Product search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary" aria-label="Search">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="col-md-4 mb-2 mb-md-0">
                        <select name="category" class="form-control" onchange="this.form.submit()">
                            <option value="">{{ __('All Categories') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @if(request('category') == $category->id) selected @endif>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Product Type Filter -->
                    <div class="col-md-4">
                        <select name="product_type" class="form-control" onchange="this.form.submit()">
                            <option value="">{{ __('All Types') }}</option>
                            @foreach ($productTypes as $type)
                                <option value="{{ $type }}" @if(request('product_type') == $type) selected @endif>
                                    {{ $type == 'physical' ? __('Physical') : __('Digital') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
