<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutContentController extends Controller
{
    public function index()
    {
        $aboutContent = AboutContent::getActive();
        return view('admin.about.index', compact('aboutContent'));
    }

    public function edit()
    {
        $aboutContent = AboutContent::getActive();

        if (!$aboutContent) {
            // Create default content if none exists
            $aboutContent = AboutContent::create([
                'title' => 'Selamat Datang di Amanah Shop',
                'content' => 'Amanah Shop adalah toko online yang menyediakan berbagai kebutuhan rumah tangga & lifestyle berkualitas. Kami menawarkan perabotan, perlengkapan kamar tidur, pakaian, sepatu, tekstil rumah, aksesoris, dan berbagai keperluan rumah tangga lainnya dengan harga terjangkau.',
                'vision' => 'Menjadi toko online terpercaya dan pilihan utama untuk kebutuhan rumah tangga & lifestyle dengan menawarkan produk berkualitas dan sistem pembayaran yang mudah.',
                'mission' => "Produk berkualitas harga terjangkau\nPembayaran tunai & cicilan\nLayanan responsif & profesional\nTransaksi transparan & aman",
                'is_active' => true,
            ]);
        }

        return view('admin.about.edit', compact('aboutContent'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'years_operating' => 'required|integer|min:0',
            'happy_customers' => 'required|integer|min:0',
            'products_sold' => 'required|integer|min:0',
            'team_members' => 'required|integer|min:0',
            'product_variants' => 'required|integer|min:0',
        ]);

        $aboutContent = AboutContent::getActive();

        if (!$aboutContent) {
            $aboutContent = new AboutContent();
            $aboutContent->is_active = true;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($aboutContent->image) {
                Storage::disk('public')->delete($aboutContent->image);
            }

            $validated['image'] = $request->file('image')->store('about', 'public');
        }

        $aboutContent->fill($validated);
        $aboutContent->save();

        return redirect()->route('admin.about.index')
            ->with('success', 'Konten halaman About berhasil diperbarui');
    }

    public function deleteImage()
    {
        $aboutContent = AboutContent::getActive();

        if ($aboutContent && $aboutContent->image) {
            Storage::disk('public')->delete($aboutContent->image);
            $aboutContent->update(['image' => null]);

            return redirect()->back()
                ->with('success', 'Gambar berhasil dihapus');
        }

        return redirect()->back()
            ->with('error', 'Tidak ada gambar untuk dihapus');
    }
}
