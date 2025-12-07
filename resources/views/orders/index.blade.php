@extends('layouts.app')

@section('title', 'My Orders - ' . config('app.name'))

@section('content')
    <div class="container py-5">
        <h1 class="mb-4"><i class="bi bi-bag me-2"></i>My Orders</h1>

        @if($orders->count() > 0)
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="fw-bold">{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>{{ $order->items->count() }} item(s)</td>
                                        <td>${{ number_format($order->total, 2) }}</td>
                                        <td>
                                            @switch($order->status)
                                                @case('pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                    @break
                                                @case('processing')
                                                    <span class="badge bg-info">Processing</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-success">Completed</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                    @break
                                                @case('refunded')
                                                    <span class="badge bg-secondary">Refunded</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $order->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($order->payment_status == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($order->payment_status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @else
                                                <span class="badge bg-danger">{{ $order->payment_status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="bi bi-bag-x display-1 text-muted"></i>
                <h3 class="mt-4">No orders yet</h3>
                <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping!</p>
                <a href="{{ route('engines.index') }}" class="btn btn-accent btn-lg">
                    <i class="bi bi-grid me-2"></i>Browse Engines
                </a>
            </div>
        @endif
    </div>
@endsection