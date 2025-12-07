@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' - ' . config('app.name'))

@section('content')
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">My Orders</a></li>
                <li class="breadcrumb-item active">{{ $order->order_number }}</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0"><i class="bi bi-receipt me-2"></i>Order {{ $order->order_number }}</h1>
            <div>
                @switch($order->status)
                    @case('pending')
                        <span class="badge bg-warning text-dark fs-6">Pending</span>
                        @break
                    @case('processing')
                        <span class="badge bg-info fs-6">Processing</span>
                        @break
                    @case('completed')
                        <span class="badge bg-success fs-6">Completed</span>
                        @break
                    @case('cancelled')
                        <span class="badge bg-danger fs-6">Cancelled</span>
                        @break
                    @default
                        <span class="badge bg-secondary fs-6">{{ $order->status }}</span>
                @endswitch
            </div>
        </div>

        <div class="row">
            <!-- Order Details -->
            <div class="col-lg-8 mb-4">
                <!-- Order Items -->
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <i class="bi bi-box me-2"></i>Order Items
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50%;">Product</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->engine && $item->engine->image)
                                                        <img src="{{ asset('storage/' . $item->engine->image) }}" 
                                                             class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                             style="width: 60px; height: 60px;">
                                                            <i class="bi bi-gear text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        @if($item->engine)
                                                            <a href="{{ route('engines.show', $item->engine->slug) }}" class="text-decoration-none">
                                                                {{ $item->engine_name }}
                                                            </a>
                                                        @else
                                                            <span>{{ $item->engine_name }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">${{ number_format($item->price, 2) }}</td>
                                            <td class="text-center align-middle">{{ $item->quantity }}</td>
                                            <td class="text-center align-middle fw-bold">${{ number_format($item->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card">
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
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <i class="bi bi-receipt me-2"></i>Order Summary
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Order Date</span>
                            <span>{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Order Time</span>
                            <span>{{ $order->created_at->format('g:i A') }}</span>
                        </div>
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

                <!-- Payment Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-credit-card me-2"></i>Payment Information
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Method</span>
                            <span class="text-capitalize">{{ $order->payment_method }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status</span>
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($order->payment_status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @else
                                <span class="badge bg-danger">{{ $order->payment_status }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-grid gap-2">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-dark">
                        <i class="bi bi-arrow-left me-1"></i>Back to Orders
                    </a>
                    <a href="{{ route('engines.index') }}" class="btn btn-accent">
                        <i class="bi bi-grid me-1"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>

        @if($order->notes)
            <div class="card mt-4">
                <div class="card-header">
                    <i class="bi bi-chat-text me-2"></i>Order Notes
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $order->notes }}</p>
                </div>
            </div>
        @endif
    </div>
@endsection