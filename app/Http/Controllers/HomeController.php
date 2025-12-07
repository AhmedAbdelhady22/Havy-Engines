<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Engine;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Display the home page
    public function index()
    {
        // Get featured engines (limit to 6)
        $featuredEngines = Engine::where('is_active', true)
            ->where('is_featured', true)
            ->with('category')
            ->take(6)
            ->get();

        // Get all active categories
        $categories = Category::where('is_active', true)
        ->withCount(['engines' => function ($query) {
            $query->where('is_active', true);
        }])
        ->get();

        // Get latest engines (limit to 4)
        $latestEngines = Engine::where('is_active', true)
            ->with('category')
            ->latest()
            ->take(4)
            ->get();

        //Get on-sale engines
        $saleEngines = Engine::where('is_active', true)
            ->whereNotNull('sale_price')
            ->with('category')
            ->take(4)
            ->get();

        return view('home', compact(
            'featuredEngines',
            'categories',
            'latestEngines',
            'saleEngines'
        ));
    }
}
