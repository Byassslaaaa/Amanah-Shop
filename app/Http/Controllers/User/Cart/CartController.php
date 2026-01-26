<?php

namespace App\Http\Controllers\User\Cart;

use App\Http\Controllers\Controller;
use App\Models\Order\Cart;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->carts()->with(['product.category', 'product.village'])->get();

        // Group by village untuk tampilan
        $groupedByVillage = $cartItems->groupBy('product.village_id');

        // Hitung total hanya dari item yang selected
        $total = $cartItems->where('is_selected', true)->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $selectedCount = $cartItems->where('is_selected', true)->count();

        // Check if any selected item's village hasn't configured shipping
        $hasUnConfiguredShipping = $cartItems->where('is_selected', true)->contains(function ($item) {
            return !$item->product->village || !$item->product->village->origin_city_id;
        });

        return view('user.cart.index', compact('cartItems', 'groupedByVillage', 'total', 'selectedCount', 'hasUnConfiguredShipping'));
    }
    
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:100'
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;

        if ($product->status !== 'active') {
            return back()->with('error', 'Produk ini sedang tidak tersedia.');
        }

        // Check if village has configured shipping location
        if (!$product->village->origin_city_id) {
            return back()->with('error', 'Maaf, desa penjual belum mengatur lokasi pengiriman. Produk ini belum bisa dipesan saat ini.');
        }

        // Unselect semua item lain di keranjang
        Cart::where('user_id', auth()->id())->update(['is_selected' => false]);

        $cart = Cart::where('user_id', auth()->id())
                   ->where('product_id', $product->id)
                   ->first();

        if ($cart) {
            // Update quantity dan set selected = true
            $cart->update([
                'quantity' => $cart->quantity + $quantity,
                'is_selected' => true
            ]);
        } else {
            // Buat cart baru dengan selected = true
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
                'is_selected' => true
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }
    
    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }
        
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100'
        ]);
        
        $cart->update(['quantity' => $request->quantity]);
        
        return back()->with('success', 'Keranjang berhasil diperbarui!');
    }
    
    public function remove(Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    public function toggleSelection(Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cart->update(['is_selected' => !$cart->is_selected]);

        return response()->json([
            'success' => true,
            'is_selected' => $cart->is_selected
        ]);
    }

    public function selectAll(Request $request)
    {
        $selected = $request->input('selected', true);

        Cart::where('user_id', auth()->id())
            ->update(['is_selected' => $selected]);

        return response()->json([
            'success' => true,
            'selected' => $selected
        ]);
    }
}
