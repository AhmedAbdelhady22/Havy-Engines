@extends('layouts.app')

@section('title', $engine->name . ' - ' . config('app.name'))

@section('content')
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('engines.index') }}">Engines</a></li>
                <li class="breadcrumb-item"><a href="{{ route('category.show', $engine->category->slug) }}">{{ $engine->category->name }}</a></li>
                <li class="breadcrumb-item active">{{ $engine->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Product Image -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm">
                    @if($engine->image)
                        <img src="{{ asset('storage/' . $engine->image) }}" class="card-img-top" alt="{{ $engine->name }}" style="max-height: 500px; object-fit: cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height: 500px;">
                            <i class="bi bi-gear display-1 text-muted" style="font-size: 10rem;"></i>
                        </div>
                    @endif
                    
                    @if($engine->on_sale)
                        <span class="badge-sale" style="font-size: 1rem; padding: 8px 15px;">
                            {{ round((($engine->price - $engine->sale_price) / $engine->price) * 100) }}% OFF
                        </span>
                    @endif
                </div>

                <!-- Gallery (if available) -->
                @if($engine->gallery && count($engine->gallery) > 0)
                    <div class="row g-2 mt-3">
                        @foreach($engine->gallery as $image)
                            <div class="col-3">
                                <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Gallery image" style="height: 80px; object-fit: cover; cursor: pointer;">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="col-lg-6">
                <span class="badge bg-secondary mb-2">{{ $engine->category->name }}</span>
                
                @if($engine->brand)
                    <span class="badge bg-dark mb-2">{{ $engine->brand }}</span>
                @endif

                <h1 class="h2 mb-3">{{ $engine->name }}</h1>
                
                @if($engine->sku)
                    <p class="text-muted mb-3">SKU: {{ $engine->sku }}</p>
                @endif

                <!-- Price -->
                <div class="mb-4">
                    @if($engine->on_sale)
                        <span class="price-original fs-4">${{ number_format($engine->price, 2) }}</span>
                        <span class="price fs-2">${{ number_format($engine->sale_price, 2) }}</span>
                        <span class="badge bg-danger ms-2">SALE</span>
                    @else
                        <span class="price fs-2">${{ number_format($engine->price, 2) }}</span>
                    @endif
                </div>

                <!-- Short Description -->
                @if($engine->short_description)
                    <p class="lead text-muted mb-4">{{ $engine->short_description }}</p>
                @endif

                <!-- Stock Status -->
                <div class="mb-4">
                    @if($engine->in_stock)
                        <span class="badge bg-success fs-6">
                            <i class="bi bi-check-circle me-1"></i>In Stock ({{ $engine->stock_quantity }} available)
                        </span>
                    @else
                        <span class="badge bg-danger fs-6">
                            <i class="bi bi-x-circle me-1"></i>Out of Stock
                        </span>
                    @endif
                </div>

                <!-- Specifications -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Specifications</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            @if($engine->horsepower)
                                <tr>
                                    <td class="text-muted" style="width: 40%;"><i class="bi bi-speedometer2 me-2"></i>Horsepower</td>
                                    <td class="fw-bold">{{ $engine->horsepower }}</td>
                                </tr>
                            @endif
                            @if($engine->displacement)
                                <tr>
                                    <td class="text-muted"><i class="bi bi-arrows-angle-expand me-2"></i>Displacement</td>
                                    <td class="fw-bold">{{ $engine->displacement }}</td>
                                </tr>
                            @endif
                            @if($engine->fuel_type)
                                <tr>
                                    <td class="text-muted"><i class="bi bi-fuel-pump me-2"></i>Fuel Type</td>
                                    <td class="fw-bold">{{ $engine->fuel_type }}</td>
                                </tr>
                            @endif
                            @if($engine->condition)
                                <tr>
                                    <td class="text-muted"><i class="bi bi-patch-check me-2"></i>Condition</td>
                                    <td class="fw-bold text-capitalize">{{ $engine->condition }}</td>
                                </tr>
                            @endif
                            @if($engine->brand)
                                <tr>
                                    <td class="text-muted"><i class="bi bi-building me-2"></i>Brand</td>
                                    <td class="fw-bold">{{ $engine->brand }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Add to Cart Form -->
                @if($engine->in_stock)
                    <div class="card border-accent mb-4">
                        <div class="card-body">
                            <form id="add-to-cart-form" class="row g-3 align-items-end">
                                <div class="col-auto">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control quantity-input" id="quantity" name="quantity" 
                                           value="1" min="1" max="{{ $engine->stock_quantity }}">
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-accent btn-lg w-100" id="add-to-cart-btn">
                                        <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        This engine is currently out of stock. Please check back later.
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="d-flex gap-2 mb-4">
                    <a href="{{ route('engines.index', ['category' => $engine->category->slug]) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-grid me-1"></i>More {{ $engine->category->name }}
                    </a>
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-dark">
                        <i class="bi bi-cart me-1"></i>View Cart
                    </a>
                </div>
            </div>
        </div>

        <!-- Full Description -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>Full Description</h5>
                    </div>
                    <div class="card-body">
                        <div class="description-content">
                            {!! nl2br(e($engine->description)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Engines -->
        @if($relatedEngines->count() > 0)
            <div class="mt-5">
                <h3 class="mb-4"><i class="bi bi-collection me-2"></i>Related Engines</h3>
                <div class="row g-4">
                    @foreach($relatedEngines as $related)
                        <div class="col-md-6 col-lg-3">
                            <div class="card engine-card h-100 position-relative">
                                @if($related->on_sale)
                                    <span class="badge-sale">SALE</span>
                                @endif
                                
                                @if($related->image)
                                    <img src="{{ asset('storage/' . $related->image) }}" class="card-img-top" alt="{{ $related->name }}">
                                @else
                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light">
                                        <i class="bi bi-gear display-4 text-muted"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body">
                                    <h6 class="card-title">{{ Str::limit($related->name, 30) }}</h6>
                                    <div class="mb-2">
                                        @if($related->on_sale)
                                            <span class="price-original small">${{ number_format($related->price, 2) }}</span>
                                            <span class="price">${{ number_format($related->sale_price, 2) }}</span>
                                        @else
                                            <span class="price">${{ number_format($related->price, 2) }}</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('engines.show', $related->slug) }}" class="btn btn-outline-accent btn-sm w-100">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('add-to-cart-btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Adding...';
        btn.disabled = true;

        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                engine_id: {{ $engine->id }},
                quantity: document.getElementById('quantity').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                updateCartCount(data.cartCount);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            showToast('Something went wrong. Please try again.', 'error');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    });
</script>
@endpush