<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Engine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EngineController extends Controller
{
    /**
     * Display a listing of engines
     */
    public function index()
    {
        $engines = Engine::with('category')->latest()->paginate(15);
        return view('admin.engines.index', compact('engines'));
    }

    /**
     * Show the form for creating a new engine
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.engines.create', compact('categories'));
    }

    /**
     * Store a newly created engine
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:50|unique:engines,sku',
            'brand' => 'nullable|string|max:100',
            'horsepower' => 'nullable|string|max:50',
            'displacement' => 'nullable|string|max:50',
            'fuel_type' => 'nullable|string|max:50',
            'condition' => 'required|in:new,used,refurbished',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        // Handle main image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('engines', 'public');
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $galleryImage) {
                $galleryPaths[] = $galleryImage->store('engines/gallery', 'public');
            }
            $validated['gallery'] = $galleryPaths;
        }

        Engine::create($validated);

        return redirect()->route('admin.engines.index')
            ->with('success', 'Engine created successfully!');
    }

    /**
     * Show the form for editing an engine
     */
    public function edit(Engine $engine)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.engines.edit', compact('engine', 'categories'));
    }

    /**
     * Update the specified engine
     */
    public function update(Request $request, Engine $engine)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:50|unique:engines,sku,' . $engine->id,
            'brand' => 'nullable|string|max:100',
            'horsepower' => 'nullable|string|max:50',
            'displacement' => 'nullable|string|max:50',
            'fuel_type' => 'nullable|string|max:50',
            'condition' => 'required|in:new,used,refurbished',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($engine->image) {
                Storage::disk('public')->delete($engine->image);
            }
            $validated['image'] = $request->file('image')->store('engines', 'public');
        }

        // Handle removing main image
        if ($request->has('remove_image') && $request->remove_image) {
            if ($engine->image) {
                Storage::disk('public')->delete($engine->image);
            }
            $validated['image'] = null;
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery')) {
            $galleryPaths = $engine->gallery ?? [];
            foreach ($request->file('gallery') as $galleryImage) {
                $galleryPaths[] = $galleryImage->store('engines/gallery', 'public');
            }
            $validated['gallery'] = $galleryPaths;
        }

        // Handle removing gallery images
        if ($request->has('remove_gallery')) {
            $currentGallery = $engine->gallery ?? [];
            $removeGallery = $request->remove_gallery;
            
            foreach ($removeGallery as $index) {
                if (isset($currentGallery[$index])) {
                    Storage::disk('public')->delete($currentGallery[$index]);
                    unset($currentGallery[$index]);
                }
            }
            
            $validated['gallery'] = array_values($currentGallery); // Re-index array
        }

        $engine->update($validated);

        return redirect()->route('admin.engines.index')
            ->with('success', 'Engine updated successfully!');
    }

    /**
     * Remove the specified engine
     */
    public function destroy(Engine $engine)
    {
        // Delete main image
        if ($engine->image) {
            Storage::disk('public')->delete($engine->image);
        }

        // Delete gallery images
        if ($engine->gallery) {
            foreach ($engine->gallery as $galleryImage) {
                Storage::disk('public')->delete($galleryImage);
            }
        }

        $engine->delete();

        return redirect()->route('admin.engines.index')
            ->with('success', 'Engine deleted successfully!');
    }

    /**
     * Display the specified engine (not typically used in admin)
     */
    public function show(Engine $engine)
    {
        return redirect()->route('admin.engines.edit', $engine);
    }
}
