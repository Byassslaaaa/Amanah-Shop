<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's favorites
     */
    public function index()
    {
        $favorites = Auth::user()->favorites()
            ->with(['product.category'])
            ->latest()
            ->paginate(12);

        return view('user.favorites.index', compact('favorites'));
    }

    /**
     * Toggle favorite status (add or remove)
     */
    public function toggle(Request $request, Product $product)
    {
        $user = Auth::user();

        $favorite = Favorite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            // Remove from favorites
            $favorite->delete();
            $message = 'Produk dihapus dari favorit';
            $isFavorited = false;
        } else {
            // Add to favorites
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $message = 'Produk ditambahkan ke favorit';
            $isFavorited = true;
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'isFavorited' => $isFavorited,
                'favoritesCount' => $user->favorites()->count(),
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Remove the specified favorite
     */
    public function destroy(Favorite $favorite)
    {
        // Ensure user owns this favorite
        if ($favorite->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $favorite->delete();

        return back()->with('success', 'Produk dihapus dari favorit');
    }
}
