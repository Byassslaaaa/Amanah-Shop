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
        // Get a mix of barang and jasa products to ensure variety
        $barangProducts = Product::with(['category'])
            ->withCount(['approvedReviews as reviews_count'])
            ->withAvg(['approvedReviews as average_rating'], 'rating')
            ->where('products.status', 'active')
            ->where('products.stock', '>', 0)
            ->where('products.type', 'barang')
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
            ->take(6)
            ->get();

        $jasaProducts = Product::with(['category'])
            ->withCount(['approvedReviews as reviews_count'])
            ->withAvg(['approvedReviews as average_rating'], 'rating')
            ->where('products.status', 'active')
            ->where('products.stock', '>', 0)
            ->where('products.type', 'jasa')
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
            ->take(2)
            ->get();

        // Merge barang and jasa products
        $featuredProducts = $barangProducts->merge($jasaProducts);

        return view('user.home', compact('categories', 'featuredProducts'));
    }

    public function about()
    {
        return view('user.about');
    }

    public function contact()
    {
        return view('user.contact');
    }
}
