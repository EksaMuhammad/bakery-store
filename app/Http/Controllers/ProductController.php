<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display the landing page with featured products.
     */
    public function index()
    {
        // Get 4 featured products (take first 4 or random)
        $featuredProducts = Product::where('stock', '>', 0)->take(4)->get();
        return view('home', compact('featuredProducts'));
    }

    /**
     * Display the product catalog with search and filters.
     */
    public function catalog(Request $request)
    {
        $query = Product::query();

        // Search by name or description
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category != '' && $request->category != 'all') {
            $query->where('category', $request->category);
        }

        // Filter by price max
        if ($request->has('price_max') && $request->price_max != '') {
            $query->where('price', '<=', $request->price_max);
        }

        // Sort products
        $sort = $request->get('sort', 'newest');
        if ($sort == 'price_low') {
            $query->orderBy('price', 'asc');
        } elseif ($sort == 'price_high') {
            $query->orderBy('price', 'desc');
        } elseif ($sort == 'name_asc') {
            $query->orderBy('name', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->get();

        // Get categories for display in filters
        $categories = [
            'breads' => 'Roti (Breads)',
            'cakes' => 'Kue (Cakes)',
            'pastries' => 'Pastri (Pastries)',
            'cookies' => 'Kue Kering (Cookies)'
        ];

        return view('catalog', compact('products', 'categories'));
    }

    /**
     * Display the product detail page.
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        
        // Get related products (same category, excluding current)
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(3)
            ->get();

        return view('detail', compact('product', 'relatedProducts'));
    }
}
