<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product\Category;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with(['products' => function($query) {
            $query->active()
                ->inStock()
                ->withCount(['approvedReviews as reviews_count'])
                ->withAvg(['approvedReviews as average_rating'], 'rating')
                ->latest();
        }])->has('products')->get();

        // Best Seller Products - based on actual sales data
        $featuredProducts = Product::with(['category'])
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
            ->take(8)
            ->get();

        return view('user.home', compact('categories', 'featuredProducts'));
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
