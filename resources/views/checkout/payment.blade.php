@extends('layouts.app')

@section('title', 'Payment - ' . config('app.name'))

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <i class="bi bi-credit-card me-2"></i>Payment
                    </div>
                    <div class="card-body text-center py-5">
                        <i class="bi bi-credit-card-2-front display-1 text-muted mb-4"></i>
                        <h4>Payment Integration Coming Soon</h4>
                        <p class="text-muted mb-4">
                            Stripe payment integration will be available in the next phase.
                            For now, you can simulate a successful payment.
                        </p>

                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Order #{{ $order->order_number }}</h5>
                                <p class="mb-1"><strong>Total Amount:</strong> ${{ number_format($order->total, 2) }}</p>
                                <p class="mb-0"><strong>Items:</strong> {{ $order->items->count() }} item(s)</p>
                            </div>
                        </div>

                        <!-- Simulate Payment -->
                        <form action="{{ route('checkout.success', $order->id) }}" method="GET">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Simulate Successful Payment
                            </button>
                        </form>

                        <div class="mt-3">
                            <a href="{{ route('checkout.cancel', $order->id) }}" class="btn btn-outline-danger">
                                <i class="bi bi-x-circle me-2"></i>Cancel Order
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection