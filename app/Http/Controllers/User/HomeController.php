<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product\Category;
use App\Models\Product\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Get active banners
        $banners = Banner::active()->ordered()->get();

        $categories = Category::with(['products' => function($query) {
            $query->active()
                ->inStock()
                ->withCount(['approvedReviews as reviews_count'])
                ->withAvg(['approvedReviews as average_rating'], 'rating')
                ->latest();
        }])->has('products')->get();

        // TERLARIS - Best Seller Products based on actual sales data (all time)
        $topSellingProducts = Product::with(['category'])
            ->withCount(['approvedReviews as reviews_count'])
            ->withAvg(['approvedReviews as average_rating'], 'rating')
            ->where('products.status', 'active')
            ->where('products.stock', '>', 0)
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function($join) {
                $join->on('order_items.order_id', '=', 'orders.id')
                     ->whereIn('orders.status', ['completed', 'processing', 'shipped'])
                     ->where('orders.payment_status', 'paid');
            })
            ->selectRaw('products.id, products.name, products.slug, products.description, products.price, products.stock, products.images, products.category_id, products.type, products.whatsapp_number, products.status, products.sku, products.created_at, products.updated_at, COALESCE(SUM(order_items.quantity), 0) as total_sold')
            ->groupBy('products.id', 'products.name', 'products.slug', 'products.description', 'products.price', 'products.stock', 'products.images', 'products.category_id', 'products.type', 'products.whatsapp_number', 'products.status', 'products.sku', 'products.created_at', 'products.updated_at')
            ->orderByDesc('total_sold')
            ->orderBy('products.id', 'asc')
            ->take(3)
            ->get();

        // Fallback: if no products with sales, show newest products
        if ($topSellingProducts->isEmpty()) {
            $topSellingProducts = Product::with(['category'])
                ->withCount(['approvedReviews as reviews_count'])
                ->withAvg(['approvedReviews as average_rating'], 'rating')
                ->where('status', 'active')
                ->where('stock', '>', 0)
                ->latest()
                ->take(3)
                ->get();
        }

        // TRENDING - Products with highest sales in last 30 days
        $trendingProducts = Product::with(['category'])
            ->withCount(['approvedReviews as reviews_count'])
            ->withAvg(['approvedReviews as average_rating'], 'rating')
            ->where('products.status', 'active')
            ->where('products.stock', '>', 0)
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', function($join) {
                $join->on('order_items.order_id', '=', 'orders.id')
                     ->whereIn('orders.status', ['completed', 'processing', 'shipped'])
                     ->where('orders.payment_status', 'paid')
                     ->where('orders.created_at', '>=', now()->subDays(30));
            })
            ->selectRaw('products.id, products.name, products.slug, products.description, products.price, products.stock, products.images, products.category_id, products.type, products.whatsapp_number, products.status, products.sku, products.created_at, products.updated_at, COALESCE(SUM(order_items.quantity), 0) as recent_sold')
            ->groupBy('products.id', 'products.name', 'products.slug', 'products.description', 'products.price', 'products.stock', 'products.images', 'products.category_id', 'products.type', 'products.whatsapp_number', 'products.status', 'products.sku', 'products.created_at', 'products.updated_at')
            ->orderByDesc('recent_sold')
            ->orderBy('products.id', 'asc')
            ->take(3)
            ->get();

        // Fallback: if no recent sales, show products with most views or newest
        if ($trendingProducts->isEmpty() || $trendingProducts->sum('recent_sold') == 0) {
            $trendingProducts = Product::with(['category'])
                ->withCount(['approvedReviews as reviews_count'])
                ->withAvg(['approvedReviews as average_rating'], 'rating')
                ->where('status', 'active')
                ->where('stock', '>', 0)
                ->latest()
                ->take(3)
                ->get();
        }

        // BARU DITAMBAHKAN - Recently added products
        $recentlyAddedProducts = Product::with(['category'])
            ->withCount(['approvedReviews as reviews_count'])
            ->withAvg(['approvedReviews as average_rating'], 'rating')
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // RATING TERTINGGI - Top rated products
        $topRatedProducts = Product::with(['category'])
            ->withCount(['approvedReviews as reviews_count'])
            ->withAvg(['approvedReviews as average_rating'], 'rating')
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->has('approvedReviews')
            ->orderByDesc('average_rating')
            ->orderBy('id', 'asc')
            ->take(3)
            ->get();

        // Fallback: if no products with reviews, show newest products
        if ($topRatedProducts->isEmpty()) {
            $topRatedProducts = Product::with(['category'])
                ->withCount(['approvedReviews as reviews_count'])
                ->withAvg(['approvedReviews as average_rating'], 'rating')
                ->where('status', 'active')
                ->where('stock', '>', 0)
                ->latest()
                ->take(3)
                ->get();
        }

        // Featured products for Deals of the Day section
        $featuredProducts = Product::with(['category'])
            ->withCount(['approvedReviews as reviews_count'])
            ->withAvg(['approvedReviews as average_rating'], 'rating')
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // BARU DILIHAT - Recently viewed products by current user
        $recentlyViewedProducts = collect();
        $recentlyViewedIds = session()->get('recently_viewed', []);

        if (!empty($recentlyViewedIds)) {
            // Get products in the same order as they were viewed
            $recentlyViewedProducts = Product::with(['category'])
                ->withCount(['approvedReviews as reviews_count'])
                ->withAvg(['approvedReviews as average_rating'], 'rating')
                ->whereIn('id', $recentlyViewedIds)
                ->where('status', 'active')
                ->get()
                ->sortBy(function($product) use ($recentlyViewedIds) {
                    return array_search($product->id, $recentlyViewedIds);
                })
                ->take(3);
        }

        return view('user.home', compact(
            'banners',
            'categories',
            'topSellingProducts',
            'trendingProducts',
            'recentlyAddedProducts',
            'topRatedProducts',
            'featuredProducts',
            'recentlyViewedProducts'
        ));
    }

    public function about()
    {
        // TODO: Implement AboutContent model and fetch from database
        // For now, using static content from the view
        $aboutContent = null;

        return view('user.about', compact('aboutContent'));
    }

    public function contact()
    {
        return view('user.contact');
    }
}
