@extends('layouts.app')

@section('title', 'Order Cancelled - ' . config('app.name'))

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center">
                    <div class="bg-warning text-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                         style="width: 100px; height: 100px;">
                        <i class="bi bi-x-lg display-4"></i>
                    </div>
                    <h1 class="h2 mb-3">Order Cancelled</h1>
                    <p class="text-muted mb-4">
                        Your order #{{ $order->order_number }} has been cancelled.
                        No payment has been processed.
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('cart.index') }}" class="btn btn-dark">
                            <i class="bi bi-cart me-1"></i>View Cart
                        </a>
                        <a href="{{ route('engines.index') }}" class="btn btn-accent">
                            <i class="bi bi-grid me-1"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection