<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Helpers\WhatsappHelper;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category'])->active();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category ID
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by category name (from navbar dropdown)
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');

        if (in_array($sortBy, ['name', 'price', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $products = $query->paginate(12);
        $categories = Category::has('products')->get();

        // Get selected category name if filtering by category
        $selectedCategoryName = null;
        if ($request->has('category') && $request->category) {
            $selectedCategoryName = $request->category;
        } elseif ($request->has('category_id') && $request->category_id) {
            $category = Category::find($request->category_id);
            if ($category) {
                $selectedCategoryName = $category->name;
            }
        }

        return view('user.products.index', compact('products', 'categories', 'selectedCategoryName'));
    }
    
    public function show(Product $product)
    {
        $product->load('category');
        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->take(4)
            ->get();

        // Track recently viewed products
        $recentlyViewed = session()->get('recently_viewed', []);

        // Remove the current product if it already exists in the list
        $recentlyViewed = array_filter($recentlyViewed, function($id) use ($product) {
            return $id != $product->id;
        });

        // Add the current product to the beginning of the array
        array_unshift($recentlyViewed, $product->id);

        // Keep only the last 10 viewed products
        $recentlyViewed = array_slice($recentlyViewed, 0, 10);

        // Save back to session
        session()->put('recently_viewed', $recentlyViewed);

        return view('user.products.show', compact('product', 'relatedProducts'));
    }
    
    public function category(Category $category)
    {
        $products = Product::with('category')
            ->where('category_id', $category->id)
            ->active()
            ->paginate(12);
            
        return view('user.products.category', compact('products', 'category'));
    }

    public function byType($type, Request $request)
    {
        if (!in_array($type, ['barang', 'jasa'])) {
            abort(404);
        }

        $query = Product::with('category')->active()->where('type', $type);
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category ID
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by category name (from navbar dropdown)
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        if (in_array($sortBy, ['name', 'price', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $products = $query->paginate(12);
        $categories = Category::where('type', $type)->has('products')->get();
        
        $typeLabel = $type === 'barang' ? 'Produk Barang' : 'Produk Jasa';
        
        return view('user.products.type', compact('products', 'categories', 'type', 'typeLabel'));
    }
    
    /**
     * Generate WhatsApp URL for product inquiry
     */
    public function whatsappInquiry(Product $product, Request $request)
    {
        $customMessage = $request->input('message');
        $includeDetails = $request->boolean('include_details', true);
        
        if ($customMessage) {
            $message = WhatsappHelper::generateCustomMessage($product, $customMessage, $includeDetails);
        } else {
            $message = WhatsappHelper::generateDefaultMessage($product);
        }
        
        $whatsappUrl = WhatsappHelper::generateProductInquiryUrl($product, $message);
        
        if (!$whatsappUrl) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor WhatsApp tidak tersedia untuk produk ini.'
            ], 400);
        }
        
        return response()->json([
            'success' => true,
            'whatsapp_url' => $whatsappUrl,
            'message' => $message
        ]);
    }
}
