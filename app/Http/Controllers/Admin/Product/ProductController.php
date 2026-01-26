<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category']);

        // Search functionality
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Low stock filter
        if ($request->filled('low_stock')) {
            $query->where('stock', '<=', 10);
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:barang,jasa',
            'whatsapp_number' => 'nullable|string|max:20',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive'
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $compressedImage = Product::compressImage($image);
                    $images[] = $compressedImage;
                } catch (\Exception $e) {
                    // If compression fails, skip this image
                    continue;
                }
            }
        }

        Product::create([
            'name' => $request->name,
            'slug' => $this->generateUniqueSlug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'type' => $request->type,
            'whatsapp_number' => $request->whatsapp_number,
            'images' => $images,
            'status' => $request->status
        ]);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load('category');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:barang,jasa',
            'whatsapp_number' => 'nullable|string|max:20',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive'
        ]);

        $images = $product->images ?? [];

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $compressedImage = Product::compressImage($image);
                    $images[] = $compressedImage;
                } catch (\Exception $e) {
                    // If compression fails, skip this image
                    continue;
                }
            }
        }

        // Handle image removals
        if ($request->filled('remove_images')) {
            $removeImages = $request->remove_images;
            foreach ($removeImages as $imageToRemove) {
                if (($key = array_search($imageToRemove, $images)) !== false) {
                    // No need to delete files since images are stored in database
                    unset($images[$key]);
                }
            }
            $images = array_values($images); // Reindex array
        }

        $updateData = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'type' => $request->type,
            'whatsapp_number' => $request->whatsapp_number,
            'images' => $images,
            'status' => $request->status
        ];

        // Only update slug if name has changed
        if ($product->name !== $request->name) {
            $updateData['slug'] = $this->generateUniqueSlug($request->name, $product->id);
        }

        $product->update($updateData);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // Images are stored in database as base64, no file deletion needed
        $product->delete();

        return redirect()->route('admin.products.index')
                        ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Generate a unique slug from the given name
     */
    private function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Product::where('slug', $slug);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
