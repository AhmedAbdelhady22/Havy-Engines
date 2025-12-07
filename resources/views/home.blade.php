@extends('layouts.app')

@section('title', 'Home - ' . config('app.name'))

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Power Your World with Heavy-Duty Engines</h1>
                    <p class="lead mb-4">Discover our premium selection of diesel, gas, marine, and industrial engines. Built for performance, engineered for reliability.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('engines.index') }}" class="btn btn-accent btn-lg">
                            <i class="bi bi-grid me-2"></i>Browse Engines
                        </a>
                        <a href="#categories" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-arrow-down me-2"></i>View Categories
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center mt-4 mt-lg-0">
                    <i class="bi bi-gear-wide-connected display-1" style="font-size: 15rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Engines Section -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0"><i class="bi bi-star-fill text-warning me-2"></i>Featured Engines</h2>
                <a href="{{ route('engines.index') }}" class="btn btn-outline-dark">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @forelse($featuredEngines as $engine)
                    <div class="col-md-6 col-lg-4">
                        <div class="card engine-card h-100 position-relative">
                            @if($engine->on_sale)
                                <span class="badge-sale">SALE</span>
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
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-speedometer2 me-1"></i>{{ $engine->horsepower }}
                                    <span class="mx-2">|</span>
                                    <i class="bi bi-fuel-pump me-1"></i>{{ $engine->fuel_type }}
                                </p>
                                <p class="card-text text-muted small flex-grow-1">
                                    {{ Str::limit($engine->short_description, 80) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <div>
                                        @if($engine->on_sale)
                                            <span class="price-original">${{ number_format($engine->price, 2) }}</span>
                                            <span class="price">${{ number_format($engine->sale_price, 2) }}</span>
                                        @else
                                            <span class="price">${{ number_format($engine->price, 2) }}</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('engines.show', $engine->slug) }}" class="btn btn-accent btn-sm">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="text-muted mt-3">No featured engines available at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5 bg-light" id="categories">
        <div class="container">
            <h2 class="text-center mb-5"><i class="bi bi-collection me-2"></i>Shop by Category</h2>
            
            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('category.show', $category->slug) }}" class="text-decoration-none">
                            <div class="category-card h-100">
                                <i class="bi bi-gear-wide display-4 mb-3"></i>
                                <h5>{{ $category->name }}</h5>
                                <p class="mb-2 opacity-75">{{ Str::limit($category->description, 60) }}</p>
                                <span class="badge bg-light text-dark">
                                    {{ $category->engines_count }} Engines
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- On Sale Section -->
    @if($saleEngines->count() > 0)
        <section class="py-5">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0"><i class="bi bi-tag-fill text-danger me-2"></i>On Sale Now</h2>
                    <a href="{{ route('engines.index') }}" class="btn btn-outline-danger">
                        See All Deals <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="row g-4">
                    @foreach($saleEngines as $engine)
                        <div class="col-md-6 col-lg-3">
                            <div class="card engine-card h-100 position-relative">
                                <span class="badge-sale">
                                    {{ round((($engine->price - $engine->sale_price) / $engine->price) * 100) }}% OFF
                                </span>
                                
                                @if($engine->image)
                                    <img src="{{ asset('storage/' . $engine->image) }}" class="card-img-top" alt="{{ $engine->name }}">
                                @else
                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light">
                                        <i class="bi bi-gear display-4 text-muted"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body">
                                    <h6 class="card-title">{{ Str::limit($engine->name, 30) }}</h6>
                                    <div class="mb-2">
                                        <span class="price-original">${{ number_format($engine->price, 2) }}</span>
                                        <span class="price">${{ number_format($engine->sale_price, 2) }}</span>
                                    </div>
                                    <a href="{{ route('engines.show', $engine->slug) }}" class="btn btn-outline-accent btn-sm w-100">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Latest Additions Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0"><i class="bi bi-clock-history me-2"></i>Latest Additions</h2>
                <a href="{{ route('engines.index', ['sort' => 'latest']) }}" class="btn btn-outline-dark">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @foreach($latestEngines as $engine)
                    <div class="col-md-6 col-lg-3">
                        <div class="card engine-card h-100">
                            @if($engine->image)
                                <img src="{{ asset('storage/' . $engine->image) }}" class="card-img-top" alt="{{ $engine->name }}">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-light">
                                    <i class="bi bi-gear display-4 text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="card-body">
                                <span class="badge bg-info mb-2">New</span>
                                <h6 class="card-title">{{ Str::limit($engine->name, 30) }}</h6>
                                <p class="price mb-2">${{ number_format($engine->current_price, 2) }}</p>
                                <a href="{{ route('engines.show', $engine->slug) }}" class="btn btn-outline-accent btn-sm w-100">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose Havy Engines?</h2>
            
            <div class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="p-4">
                        <i class="bi bi-shield-check display-4 text-success mb-3"></i>
                        <h5>Quality Guaranteed</h5>
                        <p class="text-muted">All engines are thoroughly inspected and certified for quality.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4">
                        <i class="bi bi-truck display-4 text-primary mb-3"></i>
                        <h5>Fast Shipping</h5>
                        <p class="text-muted">Free shipping on orders over $10,000. Delivered to your doorstep.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4">
                        <i class="bi bi-headset display-4 text-info mb-3"></i>
                        <h5>Expert Support</h5>
                        <p class="text-muted">Our team of experts is available 24/7 to help you choose.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4">
                        <i class="bi bi-arrow-repeat display-4 text-warning mb-3"></i>
                        <h5>Easy Returns</h5>
                        <p class="text-muted">30-day return policy for your peace of mind.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection