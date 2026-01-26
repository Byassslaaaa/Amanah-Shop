<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Category;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('products');
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $categories = $query->latest()->paginate(10);
        
        return view('admin.categories.index', compact('categories'));
    }
    
    public function create()
    {
        return view('admin.categories.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('categories', 'public');
        }
        
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image
        ]);
        
        return redirect()->route('admin.categories.index')
                        ->with('success', 'Kategori berhasil ditambahkan.');
    }
    
    public function show(Category $category)
    {
        $category->load('products');
        $products = $category->products()->paginate(12);
        
        return view('admin.categories.show', compact('category', 'products'));
    }
    
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }
    
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $image = $category->image;
        
        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($image) {
                Storage::disk('public')->delete($image);
            }
            $image = $request->file('image')->store('categories', 'public');
        }
        
        // Handle image removal
        if ($request->filled('remove_image') && $image) {
            Storage::disk('public')->delete($image);
            $image = null;
        }
        
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image
        ]);
        
        return redirect()->route('admin.categories.index')
                        ->with('success', 'Kategori berhasil diperbarui.');
    }
    
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                            ->with('error', 'Tidak dapat menghapus kategori yang masih memiliki produk.');
        }
        
        // Delete associated image
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
                        ->with('success', 'Kategori berhasil dihapus.');
    }
}
