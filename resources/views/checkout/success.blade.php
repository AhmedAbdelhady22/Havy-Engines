@extends('layouts.app')

@section('title', 'Order Confirmed - ' . config('app.name'))

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                         style="width: 100px; height: 100px;">
                        <i class="bi bi-check-lg display-4"></i>
                    </div>
                    <h1 class="h2 mb-3">Thank You for Your Order!</h1>
                    <p class="lead text-muted">Your order has been placed successfully.</p>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <i class="bi bi-receipt me-2"></i>Order Details
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">Order Number</h6>
                                <p class="h5 mb-0">{{ $order->order_number }}</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h6 class="text-muted mb-1">Order Date</h6>
                                <p class="mb-0">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">Items Ordered</h6>
                        @foreach($order->items as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span>{{ $item->engine_name }}</span>
                                    <small class="text-muted">× {{ $item->quantity }}</small>
                                </div>
                                <span>${{ number_format($item->total, 2) }}</span>
                            </div>
                        @endforeach

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span>${{ number_format($order->tax, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span>
                                @if($order->shipping_cost == 0)
                                    <span class="text-success">FREE</span>
                                @else
                                    ${{ number_format($order->shipping_cost, 2) }}
                                @endif
                            </span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="h5 mb-0">Total</span>
                            <span class="h5 mb-0">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-truck me-2"></i>Shipping Address
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>{{ $order->shipping_name }}</strong></p>
                        <p class="mb-1">{{ $order->shipping_address }}</p>
                        <p class="mb-1">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                        <p class="mb-0">{{ $order->shipping_country }}</p>
                        @if($order->shipping_phone)
                            <p class="mb-0 mt-2"><i class="bi bi-phone me-1"></i>{{ $order->shipping_phone }}</p>
                        @endif
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-dark me-2">
                        <i class="bi bi-eye me-1"></i>View Order Details
                    </a>
                    <a href="{{ route('engines.index') }}" class="btn btn-accent">
                        <i class="bi bi-grid me-1"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection