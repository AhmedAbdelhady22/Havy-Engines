<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Engine;
use Illuminate\Http\Request;

class EngineController extends Controller
{
    /**
     * Display a listing of all engines
     */
    public function index(Request $request)
    {
        $query = Engine::where('is_active', true)->with('category');

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by brand
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand', $request->brand);
        }

        // Filter by fuel type
        if ($request->has('fuel_type') && $request->fuel_type != '') {
            $query->where('fuel_type', $request->fuel_type);
        }

        // Filter by condition
        if ($request->has('condition') && $request->condition != '') {
            $query->where('condition', $request->condition);
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        // Search by name or description
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        // Paginate results (12 per page)
        $engines = $query->paginate(12)->withQueryString();

        // Get filter options
        $categories = Category::where('is_active', true)->get();
        $brands = Engine::where('is_active', true)
            ->whereNotNull('brand')
            ->distinct()
            ->pluck('brand');
        $fuelTypes = Engine::where('is_active', true)
            ->whereNotNull('fuel_type')
            ->distinct()
            ->pluck('fuel_type');

        return view('engines.index', compact(
            'engines',
            'categories',
            'brands',
            'fuelTypes'
        ));
    }

    /**
     * Display a single engine
     */
    public function show($slug)
    {
        $engine = Engine::where('slug', $slug)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();

        // Get related engines from the same category
        $relatedEngines = Engine::where('category_id', $engine->category_id)
            ->where('id', '!=', $engine->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('engines.show', compact('engine', 'relatedEngines'));
    }

    /**
     * Display engines by category
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $engines = Engine::where('category_id', $category->id)
            ->where('is_active', true)
            ->with('category')
            ->paginate(12);

        $categories = Category::where('is_active', true)->get();

        return view('engines.category', compact('category', 'engines', 'categories'));
    }
}
