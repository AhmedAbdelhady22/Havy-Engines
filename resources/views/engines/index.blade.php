@extends('layouts.app')

@section('title', 'All Engines - ' . config('app.name'))

@section('content')
    <div class="container py-5">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card filters-card">
                    <div class="card-header bg-dark text-white">
                        <i class="bi bi-funnel me-2"></i>Filter Engines
                    </div>
                    <div class="card-body">
                        <form action="{{ route('engines.index') }}" method="GET" id="filter-form">
                            <!-- Keep search query if exists -->
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <!-- Category Filter -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Category</h6>
                                <select name="category" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Brand Filter -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Brand</h6>
                                <select name="brand" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Brands</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                            {{ $brand }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Fuel Type Filter -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Fuel Type</h6>
                                <select name="fuel_type" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Fuel Types</option>
                                    @foreach($fuelTypes as $fuelType)
                                        <option value="{{ $fuelType }}" {{ request('fuel_type') == $fuelType ? 'selected' : '' }}>
                                            {{ $fuelType }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Condition Filter -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Condition</h6>
                                <select name="condition" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Conditions</option>
                                    <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>New</option>
                                    <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>Used</option>
                                    <option value="refurbished" {{ request('condition') == 'refurbished' ? 'selected' : '' }}>Refurbished</option>
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Price Range</h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" name="min_price" class="form-control" placeholder="Min" 
                                               value="{{ request('min_price') }}" min="0">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" name="max_price" class="form-control" placeholder="Max" 
                                               value="{{ request('max_price') }}" min="0">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-dark btn-sm w-100 mt-2">
                                    Apply Price Filter
                                </button>
                            </div>

                            <!-- Clear Filters -->
                            <a href="{{ route('engines.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-x-circle me-1"></i>Clear All Filters
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Engine Grid -->
            <div class="col-lg-9">
                <!-- Header with Sort -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">
                            @if(request('search'))
                                Search Results for "{{ request('search') }}"
                            @elseif(request('category'))
                                {{ $categories->firstWhere('slug', request('category'))->name ?? 'Engines' }}
                            @else
                                All Engines
                            @endif
                        </h4>
                        <p class="text-muted mb-0">{{ $engines->total() }} engines found</p>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <label class="me-2 text-muted">Sort by:</label>
                        <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href=this.value">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>
                                Latest
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                                Price: Low to High
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                                Price: High to Low
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'name']) }}" {{ request('sort') == 'name' ? 'selected' : '' }}>
                                Name: A-Z
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Engine Cards -->
                <div class="row g-4">
                    @forelse($engines as $engine)
                        <div class="col-md-6 col-lg-4">
                            <div class="card engine-card h-100 position-relative">
                                @if($engine->on_sale)
                                    <span class="badge-sale">
                                        {{ round((($engine->price - $engine->sale_price) / $engine->price) * 100) }}% OFF
                                    </span>
                                @endif
                                
                                @if($engine->image)
                                    <img src="{{ asset('storage/' . $engine->image) }}" class="card-img-top" alt="{{ $engine->name }}">
                                @else
                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light">
                                        <i class="bi bi-gear display-1 text-muted"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body d-flex flex-column">
                                    <span class="badge bg-secondary mb-2" style="width: fit-content;">
                                        {{ $engine->category->name }}
                                    </span>
                                    <h5 class="card-title">{{ $engine->name }}</h5>
                                    
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        @if($engine->horsepower)
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-speedometer2 me-1"></i>{{ $engine->horsepower }}
                                            </span>
                                        @endif
                                        @if($engine->fuel_type)
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-fuel-pump me-1"></i>{{ $engine->fuel_type }}
                                            </span>
                                        @endif
                                        @if($engine->brand)
                                            <span class="badge bg-light text-dark">
                                                {{ $engine->brand }}
                                            </span>
                                        @endif
                                    </div>

                                    <p class="card-text text-muted small flex-grow-1">
                                        {{ Str::limit($engine->short_description, 80) }}
                                    </p>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                @if($engine->on_sale)
                                                    <span class="price-original">${{ number_format($engine->price, 2) }}</span>
                                                    <span class="price">${{ number_format($engine->sale_price, 2) }}</span>
                                                @else
                                                    <span class="price">${{ number_format($engine->price, 2) }}</span>
                                                @endif
                                            </div>
                                            @if($engine->in_stock)
                                                <span class="badge bg-success">In Stock</span>
                                            @else
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @endif
                                        </div>
                                        
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('engines.show', $engine->slug) }}" class="btn btn-outline-accent">
                                                <i class="bi bi-eye me-1"></i>View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bi bi-search display-1 text-muted"></i>
                                <h4 class="mt-3">No engines found</h4>
                                <p class="text-muted">Try adjusting your filters or search terms</p>
                                <a href="{{ route('engines.index') }}" class="btn btn-accent">
                                    Clear Filters
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($engines->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $engines->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection