@extends('admin.layouts.app')

@section('title', 'Manage Engines')
@section('page-title', 'Manage Engines')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">All Engines</h4>
        <a href="{{ route('admin.engines.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Add New Engine
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Featured</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($engines as $engine)
                            <tr>
                                <td>{{ $engine->id }}</td>
                                <td>
                                    <strong>{{ Str::limit($engine->name, 30) }}</strong>
                                    @if($engine->brand)
                                        <br><small class="text-muted">{{ $engine->brand }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $engine->category->name }}</span>
                                </td>
                                <td>
                                    @if($engine->sale_price)
                                        <span class="text-decoration-line-through text-muted">${{ number_format($engine->price, 2) }}</span>
                                        <br><span class="text-success fw-bold">${{ number_format($engine->sale_price, 2) }}</span>
                                    @else
                                        ${{ number_format($engine->price, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @if($engine->stock_quantity > 0)
                                        <span class="badge bg-success">{{ $engine->stock_quantity }}</span>
                                    @else
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @endif
                                </td>
                                <td>
                                    @if($engine->is_featured)
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @else
                                        <i class="bi bi-star text-muted"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($engine->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('engines.show', $engine->slug) }}" class="btn btn-outline-secondary" target="_blank" title="View on site">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.engines.edit', $engine) }}" class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.engines.destroy', $engine) }}" method="POST" class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this engine?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox display-4"></i>
                                    <p class="mt-2">No engines found. <a href="{{ route('admin.engines.create') }}">Add one now!</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($engines->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $engines->links() }}
        </div>
    @endif
@endsection