@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Total Engines</h6>
                            <h2 class="mb-0">{{ $stats['total_engines'] }}</h2>
                        </div>
                        <i class="bi bi-gear display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Total Orders</h6>
                            <h2 class="mb-0">{{ $stats['total_orders'] }}</h2>
                        </div>
                        <i class="bi bi-bag display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Total Users</h6>
                            <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
                        </div>
                        <i class="bi bi-people display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Pending Orders</h6>
                            <h2 class="mb-0">{{ $stats['pending_orders'] }}</h2>
                        </div>
                        <i class="bi bi-clock display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Card -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-currency-dollar me-2"></i>Total Revenue</h5>
                    <h2 class="text-success">${{ number_format($stats['total_revenue'], 2) }}</h2>
                    <p class="text-muted mb-0">From paid orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.engines.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus me-1"></i>Add Engine
                        </a>
                        <a href="{{ route('admin.engines.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list me-1"></i>View All Engines
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Orders</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['recent_orders'] as $order)
                            <tr>
                                <td class="fw-bold">{{ $order->order_number }}</td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                <td>${{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No orders yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection