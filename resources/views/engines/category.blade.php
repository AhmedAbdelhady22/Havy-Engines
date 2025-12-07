@extends('layouts.app')

@section('title', $category->name . ' - ' . config('app.name'))

@section('content')
    <!-- Category Header -->
    <div class="bg-dark text-white py-5">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('engines.index') }}" class="text-white-50">Engines</a></li>
                    <li class="breadcrumb-item active text-white">{{ $category->name }}</li>
                </ol>
            </nav>
            <h1 class="mb-3"><i class="bi bi-gear-wide me-2"></i>{{ $category->name }}</h1>
            @if($category->description)
                <p class="lead mb-0 opacity-75">{{ $category->description }}</p>
            @endif
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <!-- Sidebar with Categories -->
            <div class="col-lg-3 mb-4">
                <div class="card filters-card">
                    <div class="card-header bg-dark text-white">
                        <i class="bi bi-collection me-2"></i>All Categories
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($categories as $cat)
                            <a href="{{ route('category.show', $cat->slug) }}" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $cat->id == $category->id ? 'active' : '' }}">
                                {{ $cat->name }}
                                <span class="badge {{ $cat->id == $category->id ? 'bg-white text-dark' : 'bg-secondary' }}">
                                    {{ $cat->engines()->where('is_active', true)->count() }}
                                </span>
                            </a>
                        @endforeach
                    </ul>
                </div>

                <!-- Quick Filters -->
                <div class="card filters-card mt-4">
                    <div class="card-header bg-dark text-white">
                        <i class="bi bi-funnel me-2"></i>Quick Filters
                    </div>
                    <div class="card-body">
                        <a href="{{ route('engines.index', ['category' => $category->slug, 'sort' => 'price_low']) }}" 
                           class="btn btn-outline-secondary btn-sm w-100 mb-2">
                            <i class="bi bi-sort-down me-1"></i>Price: Low to High
                        </a>
                        <a href="{{ route('engines.index', ['category' => $category->slug, 'sort' => 'price_high']) }}" 
                           class="btn btn-outline-secondary btn-sm w-100 mb-2">
                            <i class="bi bi-sort-up me-1"></i>Price: High to Low
                        </a>
                        <a href="{{ route('engines.index', ['category' => $category->slug]) }}" 
                           class="btn btn-outline-dark btn-sm w-100">
                            <i class="bi bi-sliders me-1"></i>Advanced Filters
                        </a>
                    </div>
                </div>
            </div>

            <!-- Engine Grid -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="text-muted mb-0">Showing {{ $engines->count() }} of {{ $engines->total() }} engines</p>
                </div>

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
                                    <h5 class="card-title">{{ $engine->name }}</h5>
                                    
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        @if($engine->horsepower)
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-speedometer2 me-1"></i>{{ $engine->horsepower }}
                                            </span>
                                        @endif
                                        @if($engine->brand)
                                            <span class="badge bg-light text-dark">{{ $engine->brand }}</span>
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
                                        
                                        <a href="{{ route('engines.show', $engine->slug) }}" class="btn btn-outline-accent w-100">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bi bi-inbox display-1 text-muted"></i>
                                <h4 class="mt-3">No engines in this category</h4>
                                <p class="text-muted">Check back later or browse other categories</p>
                                <a href="{{ route('engines.index') }}" class="btn btn-accent">
                                    Browse All Engines
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