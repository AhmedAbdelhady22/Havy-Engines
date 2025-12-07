@extends('layouts.app')

@section('title', 'Shopping Cart - ' . config('app.name'))

@section('content')
    <div class="container py-5">
        <h1 class="mb-4"><i class="bi bi-cart3 me-2"></i>Shopping Cart</h1>

        @if($cartItems->count() > 0)
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8 mb-4">
                    <div class="card">
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-bag me-2"></i>{{ $cartItems->count() }} Item(s) in Cart</span>
                            <button class="btn btn-outline-light btn-sm" id="clear-cart-btn">
                                <i class="bi bi-trash me-1"></i>Clear Cart
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50%;">Product</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Subtotal</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cart-items">
                                        @foreach($cartItems as $item)
                                            <tr id="cart-item-{{ $item->id }}">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($item->engine->image)
                                                            <img src="{{ asset('storage/' . $item->engine->image) }}" 
                                                                 class="cart-item-image me-3" alt="{{ $item->engine->name }}">
                                                        @else
                                                            <div class="cart-item-image me-3 d-flex align-items-center justify-content-center bg-light">
                                                                <i class="bi bi-gear text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-1">
                                                                <a href="{{ route('engines.show', $item->engine->slug) }}" class="text-decoration-none text-dark">
                                                                    {{ $item->engine->name }}
                                                                </a>
                                                            </h6>
                                                            <small class="text-muted">{{ $item->engine->category->name }}</small>
                                                            @if($item->engine->brand)
                                                                <br><small class="text-muted">Brand: {{ $item->engine->brand }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">
                                                    ${{ number_format($item->price, 2) }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="input-group input-group-sm justify-content-center" style="max-width: 120px; margin: 0 auto;">
                                                        <button class="btn btn-outline-secondary qty-decrease" data-item-id="{{ $item->id }}">
                                                            <i class="bi bi-dash"></i>
                                                        </button>
                                                        <input type="number" class="form-control text-center qty-input" 
                                                               value="{{ $item->quantity }}" min="1" max="{{ $item->engine->stock_quantity }}"
                                                               data-item-id="{{ $item->id }}" style="max-width: 50px;">
                                                        <button class="btn btn-outline-secondary qty-increase" data-item-id="{{ $item->id }}"
                                                                {{ $item->quantity >= $item->engine->stock_quantity ? 'disabled' : '' }}>
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>
                                                    <small class="text-muted">{{ $item->engine->stock_quantity }} available</small>
                                                </td>
                                                <td class="text-center align-middle fw-bold item-subtotal" data-item-id="{{ $item->id }}">
                                                    ${{ number_format($item->subtotal, 2) }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    <button class="btn btn-outline-danger btn-sm remove-item" data-item-id="{{ $item->id }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Continue Shopping -->
                    <div class="mt-3">
                        <a href="{{ route('engines.index') }}" class="btn btn-outline-dark">
                            <i class="bi bi-arrow-left me-1"></i>Continue Shopping
                        </a>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <i class="bi bi-receipt me-2"></i>Order Summary
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span>Subtotal</span>
                                <span class="fw-bold" id="cart-subtotal">${{ number_format($total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Estimated Tax (8%)</span>
                                <span id="cart-tax">${{ number_format($total * 0.08, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Shipping</span>
                                <span id="cart-shipping">
                                    @if($total > 10000)
                                        <span class="text-success">FREE</span>
                                    @else
                                        $500.00
                                    @endif
                                </span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="h5 mb-0">Total</span>
                                <span class="h5 mb-0 text-accent" id="cart-total">
                                    ${{ number_format($total + ($total * 0.08) + ($total > 10000 ? 0 : 500), 2) }}
                                </span>
                            </div>

                            @if($total < 10000)
                                <div class="alert alert-info small mb-3">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Add ${{ number_format(10000 - $total, 2) }} more for FREE shipping!
                                </div>
                            @endif

                            @auth
                                <a href="{{ route('checkout.index') }}" class="btn btn-accent btn-lg w-100">
                                    <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-accent btn-lg w-100 mb-2">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login to Checkout
                                </a>
                                <p class="text-center text-muted small mb-0">
                                    Don't have an account? <a href="{{ route('register') }}">Register here</a>
                                </p>
                            @endauth
                        </div>
                    </div>

                    <!-- Secure Payment Badge -->
                    <div class="card mt-3">
                        <div class="card-body text-center">
                            <i class="bi bi-shield-check text-success fs-4 me-2"></i>
                            <span class="text-muted">Secure Payment Guaranteed</span>
                            <div class="mt-2">
                                <i class="bi bi-credit-card fs-5 text-muted me-2"></i>
                                <i class="bi bi-paypal fs-5 text-muted me-2"></i>
                                <i class="bi bi-stripe fs-5 text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-5">
                <i class="bi bi-cart-x display-1 text-muted"></i>
                <h3 class="mt-4">Your cart is empty</h3>
                <p class="text-muted mb-4">Looks like you haven't added any engines to your cart yet.</p>
                <a href="{{ route('engines.index') }}" class="btn btn-accent btn-lg">
                    <i class="bi bi-grid me-2"></i>Browse Engines
                </a>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    // Update quantity
    function updateQuantity(itemId, quantity) {
        fetch(`/cart/update/${itemId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`.item-subtotal[data-item-id="${itemId}"]`).textContent = '$' + data.itemSubtotal;
                updateCartCount(data.cartCount);
                location.reload(); // Reload to recalculate totals
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => showToast('Error updating cart', 'error'));
    }

    // Quantity increase buttons
    document.querySelectorAll('.qty-increase').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            const input = document.querySelector(`.qty-input[data-item-id="${itemId}"]`);
            const newQty = parseInt(input.value) + 1;
            if (newQty <= parseInt(input.max)) {
                input.value = newQty;
                updateQuantity(itemId, newQty);
            }
        });
    });

    // Quantity decrease buttons
    document.querySelectorAll('.qty-decrease').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            const input = document.querySelector(`.qty-input[data-item-id="${itemId}"]`);
            const newQty = parseInt(input.value) - 1;
            if (newQty >= 1) {
                input.value = newQty;
                updateQuantity(itemId, newQty);
            }
        });
    });

    // Direct quantity input change
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            const itemId = this.dataset.itemId;
            let newQty = parseInt(this.value);
            if (newQty < 1) newQty = 1;
            if (newQty > parseInt(this.max)) newQty = parseInt(this.max);
            this.value = newQty;
            updateQuantity(itemId, newQty);
        });
    });

    // Remove item
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Remove this item from cart?')) return;
            
            const itemId = this.dataset.itemId;
            fetch(`/cart/remove/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    document.getElementById(`cart-item-${itemId}`).remove();
                    updateCartCount(data.cartCount);
                    if (data.cartCount === 0) {
                        location.reload();
                    } else {
                        location.reload(); // Reload to recalculate totals
                    }
                }
            })
            .catch(error => showToast('Error removing item', 'error'));
        });
    });

    // Clear cart
    document.getElementById('clear-cart-btn')?.addEventListener('click', function() {
        if (!confirm('Clear all items from your cart?')) return;
        
        fetch('/cart/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                updateCartCount(0);
                location.reload();
            }
        })
        .catch(error => showToast('Error clearing cart', 'error'));
    });
</script>
@endpush