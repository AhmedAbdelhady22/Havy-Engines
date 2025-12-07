@extends('admin.layouts.app')

@section('title', 'Edit Engine')
@section('page-title', 'Edit Engine: ' . $engine->name)

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.engines.update', $engine) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <!-- Basic Info -->
                    <div class="col-md-8">
                        <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Basic Information</h5>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Engine Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $engine->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="short_description" class="form-label">Short Description</label>
                            <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                      id="short_description" name="short_description" rows="2">{{ old('short_description', $engine->short_description) }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Full Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5" required>{{ old('description', $engine->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image Upload Section -->
                        <h5 class="mb-3 mt-4"><i class="bi bi-image me-2"></i>Images</h5>
                        
                        <!-- Current Main Image -->
                        <div class="mb-3">
                            <label class="form-label">Current Main Image</label>
                            @if($engine->image)
                                <div class="mb-2 position-relative d-inline-block">
                                    <img src="{{ asset('storage/' . $engine->image) }}" class="img-thumbnail" style="max-height: 200px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                                        <label class="form-check-label text-danger" for="remove_image">
                                            <i class="bi bi-trash me-1"></i>Remove this image
                                        </label>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">No main image uploaded</p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">{{ $engine->image ? 'Replace Main Image' : 'Upload Main Image' }}</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*" onchange="previewMainImage(this)">
                            <small class="text-muted">Accepted formats: JPEG, PNG, JPG, GIF, WEBP. Max size: 2MB</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="main-image-preview" class="mt-2"></div>
                        </div>

                        <!-- Current Gallery -->
                        <div class="mb-3">
                            <label class="form-label">Current Gallery Images</label>
                            @if($engine->gallery && count($engine->gallery) > 0)
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    @foreach($engine->gallery as $index => $galleryImage)
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $galleryImage) }}" class="img-thumbnail" 
                                                 style="width: 100px; height: 100px; object-fit: cover;">
                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="remove_gallery_{{ $index }}" name="remove_gallery[]" value="{{ $index }}">
                                                <label class="form-check-label small text-danger" for="remove_gallery_{{ $index }}">
                                                    Remove
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No gallery images uploaded</p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="gallery" class="form-label">Add More Gallery Images</label>
                            <input type="file" class="form-control @error('gallery.*') is-invalid @enderror" 
                                   id="gallery" name="gallery[]" accept="image/*" multiple onchange="previewGallery(this)">
                            <small class="text-muted">You can select multiple images. Max size: 2MB each</small>
                            @error('gallery.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="gallery-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-md-4">
                        <h5 class="mb-3"><i class="bi bi-sliders me-2"></i>Settings</h5>
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $engine->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price', $engine->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="sale_price" class="form-label">Sale Price ($)</label>
                            <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" 
                                   id="sale_price" name="sale_price" value="{{ old('sale_price', $engine->sale_price) }}">
                            @error('sale_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                   id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $engine->stock_quantity) }}" required>
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="condition" class="form-label">Condition <span class="text-danger">*</span></label>
                            <select class="form-select @error('condition') is-invalid @enderror" 
                                    id="condition" name="condition" required>
                                <option value="new" {{ old('condition', $engine->condition) == 'new' ? 'selected' : '' }}>New</option>
                                <option value="used" {{ old('condition', $engine->condition) == 'used' ? 'selected' : '' }}>Used</option>
                                <option value="refurbished" {{ old('condition', $engine->condition) == 'refurbished' ? 'selected' : '' }}>Refurbished</option>
                            </select>
                            @error('condition')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" 
                                   {{ old('is_featured', $engine->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">Featured Engine</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $engine->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active (Visible on site)</label>
                        </div>
                    </div>

                    <!-- Specifications -->
                    <div class="col-12">
                        <h5 class="mb-3"><i class="bi bi-list-check me-2"></i>Specifications</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="brand" class="form-label">Brand</label>
                                <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand', $engine->brand) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="sku" class="form-label">SKU</label>
                                <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $engine->sku) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="horsepower" class="form-label">Horsepower</label>
                                <input type="text" class="form-control" id="horsepower" name="horsepower" 
                                       value="{{ old('horsepower', $engine->horsepower) }}" placeholder="e.g., 500 HP">
                            </div>
                            <div class="col-md-3">
                                <label for="displacement" class="form-label">Displacement</label>
                                <input type="text" class="form-control" id="displacement" name="displacement" 
                                       value="{{ old('displacement', $engine->displacement) }}" placeholder="e.g., 15.0L">
                            </div>
                            <div class="col-md-3">
                                <label for="fuel_type" class="form-label">Fuel Type</label>
                                <select class="form-select" id="fuel_type" name="fuel_type">
                                    <option value="">Select</option>
                                    <option value="Diesel" {{ old('fuel_type', $engine->fuel_type) == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="Gasoline" {{ old('fuel_type', $engine->fuel_type) == 'Gasoline' ? 'selected' : '' }}>Gasoline</option>
                                    <option value="Natural Gas" {{ old('fuel_type', $engine->fuel_type) == 'Natural Gas' ? 'selected' : '' }}>Natural Gas</option>
                                    <option value="Hybrid" {{ old('fuel_type', $engine->fuel_type) == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="col-12">
                        <hr>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Update Engine
                            </button>
                            <a href="{{ route('admin.engines.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x me-1"></i>Cancel
                            </a>
                            <a href="{{ route('engines.show', $engine->slug) }}" class="btn btn-outline-info ms-auto" target="_blank">
                                <i class="bi bi-eye me-1"></i>View on Site
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewMainImage(input) {
            const preview = document.getElementById('main-image-preview');
            preview.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <p class="text-success mb-1"><small>New image preview:</small></p>
                        <img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px;">
                    `;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewGallery(input) {
            const preview = document.getElementById('gallery-preview');
            preview.innerHTML = '';
            
            if (input.files) {
                preview.innerHTML = '<p class="text-success w-100 mb-1"><small>New images to add:</small></p>';
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML += `
                            <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                        `;
                    }
                    reader.readAsDataURL(file);
                });
            }
        }
    </script>
@endsection