@extends('layouts.app')

@section('title', 'Checkout - ' . config('app.name'))

@section('content')
    <div class="container py-5">
        <h1 class="mb-4"><i class="bi bi-credit-card me-2"></i>Checkout</h1>

        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
            @csrf
            <div class="row">
                <!-- Shipping Information -->
                <div class="col-lg-7 mb-4">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <i class="bi bi-truck me-2"></i>Shipping Information
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="shipping_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('shipping_name') is-invalid @enderror" 
                                           id="shipping_name" name="shipping_name" 
                                           value="{{ old('shipping_name', Auth::user()->name) }}" required>
                                    @error('shipping_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="shipping_address" class="form-label">Street Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                              id="shipping_address" name="shipping_address" rows="2" required>{{ old('shipping_address') }}</textarea>
                                    @error('shipping_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="shipping_city" class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('shipping_city') is-invalid @enderror" 
                                           id="shipping_city" name="shipping_city" value="{{ old('shipping_city') }}" required>
                                    @error('shipping_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="shipping_state" class="form-label">State/Province</label>
                                    <input type="text" class="form-control @error('shipping_state') is-invalid @enderror" 
                                           id="shipping_state" name="shipping_state" value="{{ old('shipping_state') }}">
                                    @error('shipping_state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="shipping_zip" class="form-label">ZIP / Postal Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('shipping_zip') is-invalid @enderror" 
                                           id="shipping_zip" name="shipping_zip" value="{{ old('shipping_zip') }}" required>
                                    @error('shipping_zip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="shipping_country" class="form-label">Country <span class="text-danger">*</span></label>
                                    <select class="form-select @error('shipping_country') is-invalid @enderror" 
                                            id="shipping_country" name="shipping_country" required>
                                        <option value="">Select Country</option>
                                        <option value="US" {{ old('shipping_country') == 'US' ? 'selected' : '' }}>United States</option>
                                        <option value="CA" {{ old('shipping_country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                        <option value="UK" {{ old('shipping_country') == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="AU" {{ old('shipping_country') == 'AU' ? 'selected' : '' }}>Australia</option>
                                        <option value="DE" {{ old('shipping_country') == 'DE' ? 'selected' : '' }}>Germany</option>
                                        <option value="FR" {{ old('shipping_country') == 'FR' ? 'selected' : '' }}>France</option>
                                        <option value="EG" {{ old('shipping_country') == 'EG' ? 'selected' : '' }}>Egypt</option>
                                        <option value="AE" {{ old('shipping_country') == 'AE' ? 'selected' : '' }}>United Arab Emirates</option>
                                        <option value="SA" {{ old('shipping_country') == 'SA' ? 'selected' : '' }}>Saudi Arabia</option>
                                        <option value="OTHER" {{ old('shipping_country') == 'OTHER' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('shipping_country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="shipping_phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('shipping_phone') is-invalid @enderror" 
                                           id="shipping_phone" name="shipping_phone" value="{{ old('shipping_phone') }}"
                                           placeholder="+1 (555) 123-4567">
                                    @error('shipping_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="notes" class="form-label">Order Notes (Optional)</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3" 
                                              placeholder="Any special instructions for delivery...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Back to Cart -->
                    <div class="mt-3">
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Back to Cart
                        </a>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <i class="bi bi-receipt me-2"></i>Order Summary
                        </div>
                        <div class="card-body">
                            <!-- Cart Items -->
                            <div class="mb-4">
                                @foreach($cartItems as $item)
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            @if($item->engine->image)
                                                <img src="{{ asset('storage/' . $item->engine->image) }}" 
                                                     class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;" 
                                                     alt="{{ $item->engine->name }}">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="bi bi-gear text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ Str::limit($item->engine->name, 25) }}</h6>
                                                <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                            </div>
                                        </div>
                                        <span class="fw-bold">${{ number_format($item->subtotal, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Totals -->
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax (8%)</span>
                                <span>${{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping</span>
                                <span>
                                    @if($shipping == 0)
                                        <span class="text-success">FREE</span>
                                    @else
                                        ${{ number_format($shipping, 2) }}
                                    @endif
                                </span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="h5 mb-0">Total</span>
                                <span class="h5 mb-0 text-accent">${{ number_format($total, 2) }}</span>
                            </div>

                            <!-- Place Order Button -->
                            <button type="submit" class="btn btn-accent btn-lg w-100" id="place-order-btn">
                                <i class="bi bi-lock me-2"></i>Place Order
                            </button>

                            <p class="text-center text-muted small mt-3 mb-0">
                                <i class="bi bi-shield-check me-1"></i>
                                Your payment information is secure
                            </p>
                        </div>
                    </div>

                    <!-- Order Info -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <h6 class="mb-3"><i class="bi bi-info-circle me-2"></i>Order Information</h6>
                            <ul class="list-unstyled small text-muted mb-0">
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Secure checkout process</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Order confirmation via email</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Track your order anytime</li>
                                <li><i class="bi bi-check text-success me-2"></i>30-day return policy</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        const btn = document.getElementById('place-order-btn');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
        btn.disabled = true;
    });
</script>
@endpush