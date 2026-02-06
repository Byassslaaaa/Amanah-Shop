<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product\Product;
use App\Models\Product\ProductReview;
use App\Models\User;
use App\Models\Order;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all products
        $products = Product::all();

        // Get all users (excluding admins if role exists)
        $users = User::where('role', '!=', 'admin')->get();

        if ($products->isEmpty() || $users->isEmpty()) {
            $this->command->info('No products or users found. Please seed products and users first.');
            return;
        }

        $comments = [
            'Produk sangat bagus, kualitas terjamin!',
            'Pelayanan ramah, pengiriman cepat. Recommended!',
            'Sesuai deskripsi, packing rapi. Terima kasih!',
            'Harga terjangkau dengan kualitas baik.',
            'Produk berkualitas, sangat recommended!',
            'Sangat puas dengan pembelian ini.',
            'Kualitas produk lokal yang luar biasa!',
            'Pengiriman cepat, produk sesuai ekspektasi.',
            'Akan order lagi di lain waktu.',
            'Mantap! Dukung produk lokal!',
            'Bagus banget, original dan berkualitas.',
            'Packingnya aman, produknya fresh.',
            'Harga sebanding dengan kualitas.',
            'Respon penjual cepat, ramah.',
            'Produknya authentic, suka banget!',
        ];

        $this->command->info('Seeding product reviews...');

        // Create reviews for random products
        foreach ($products->random(min(10, $products->count())) as $product) {
            // Each product gets 3-8 random reviews
            $reviewCount = rand(3, 8);

            for ($i = 0; $i < $reviewCount; $i++) {
                $user = $users->random();
                $rating = $this->getWeightedRating(); // Most reviews will be 4-5 stars

                ProductReview::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'order_id' => null, // Optional: can be null for sample data
                    'rating' => $rating,
                    'comment' => $comments[array_rand($comments)],
                    'status' => 'approved', // Auto-approve for demo
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }

            $this->command->info("Added {$reviewCount} reviews for product: {$product->name}");
        }

        $this->command->info('Product reviews seeded successfully!');
    }

    /**
     * Get weighted rating (more 4-5 stars, less 1-2 stars)
     */
    private function getWeightedRating(): int
    {
        $rand = rand(1, 100);

        if ($rand <= 50) return 5;      // 50% chance for 5 stars
        if ($rand <= 80) return 4;      // 30% chance for 4 stars
        if ($rand <= 95) return 3;      // 15% chance for 3 stars
        if ($rand <= 98) return 2;      // 3% chance for 2 stars
        return 1;                        // 2% chance for 1 star
    }
}
