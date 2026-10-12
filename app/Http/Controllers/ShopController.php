<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with(['category', 'brand']);

        // Search
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('brand', function ($b) use ($search) {
                      $b->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('category', function ($c) use ($search) {
                      $c->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $categorySlug = $request->input('category');
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Brand Filter
        if ($request->filled('brand')) {
            $brandSlug = $request->input('brand');
            $query->whereHas('brand', function ($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            });
        }

        // Price Filter
        if ($request->filled('min_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('sale_price', '>=', $request->min_price)
                  ->orWhere(function ($sq) use ($request) {
                      $sq->whereNull('sale_price')->where('price', '>=', $request->min_price);
                  });
            });
        }
        if ($request->filled('max_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('sale_price', '<=', $request->max_price)
                  ->orWhere(function ($sq) use ($request) {
                      $sq->whereNull('sale_price')->where('price', '<=', $request->max_price);
                  });
            });
        }

        // In Stock Filter
        if ($request->boolean('in_stock')) {
            $query->where('stock_quantity', '>', 0);
        }

        // Sorting
        $sort = $request->input('sort', 'featured');
        match ($sort) {
            'price_low' => $query->orderByRaw('COALESCE(sale_price, price) ASC'),
            'price_high' => $query->orderByRaw('COALESCE(sale_price, price) DESC'),
            'newest' => $query->latest(),
            default => $query->orderBy('is_featured', 'desc')->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::where('is_active', true)->withCount('products')->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->withCount('products')->orderBy('name')->get();

        return view('shop.index', compact('products', 'categories', 'brands'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        return redirect()->route('shop.index', ['category' => $slug]);
    }

    public function brand($slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();
        return redirect()->route('shop.index', ['brand' => $slug]);
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'brand', 'reviews.user'])
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }

    public function submitReview(Request $request, $slug)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to submit a review.');
        }

        $product = Product::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => Auth::id()],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'is_approved' => true,
            ]
        );

        return back()->with('success', 'Your review has been submitted successfully! Thank you for your feedback.');
    }
}
