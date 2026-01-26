<?php

namespace App\Helpers;

use App\Models\Product\Product;

class WhatsappHelper
{
    /**
     * Generate WhatsApp URL for product inquiry
     */
    public static function generateProductInquiryUrl(Product $product, $message = null)
    {
        $phoneNumber = self::formatPhoneNumber($product->whatsapp_number);
        
        if (!$phoneNumber) {
            return null;
        }
        
        $defaultMessage = self::generateDefaultMessage($product);
        $finalMessage = $message ?? $defaultMessage;
        
        return "https://wa.me/{$phoneNumber}?text=" . urlencode($finalMessage);
    }
    
    /**
     * Format phone number to international format
     */
    public static function formatPhoneNumber($phoneNumber)
    {
        if (!$phoneNumber) {
            return null;
        }
        
        // Remove all non-numeric characters
        $phone = preg_replace('/\D/', '', $phoneNumber);
        
        // Handle Indonesian phone numbers
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        } elseif (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }
    
    /**
     * Generate default message for product inquiry
     */
    public static function generateDefaultMessage(Product $product)
    {
        $productUrl = route('products.show', $product->slug);
        
        $message = "Halo! Saya tertarik dengan produk:\n\n";
        $message .= "🛍️ *{$product->name}*\n";
        $message .= "💰 Harga: Rp " . number_format($product->price, 0, ',', '.') . "\n";
        
        if ($product->type === 'barang' && $product->stock > 0) {
            $message .= "📦 Stok: {$product->stock} unit\n";
        }
        
        $message .= "\n📱 Link produk: {$productUrl}\n\n";
        $message .= "Apakah masih tersedia? Bagaimana cara pemesanannya?";
        
        return $message;
    }
    
    /**
     * Generate custom message for product inquiry
     */
    public static function generateCustomMessage(Product $product, $customText, $includeDetails = true)
    {
        if (!$includeDetails) {
            return $customText;
        }
        
        $productUrl = route('products.show', $product->slug);
        
        $message = $customText . "\n\n";
        $message .= "📋 *Detail Produk:*\n";
        $message .= "🛍️ Nama: {$product->name}\n";
        $message .= "💰 Harga: Rp " . number_format($product->price, 0, ',', '.') . "\n";
        
        if ($product->type === 'barang' && $product->stock > 0) {
            $message .= "📦 Stok: {$product->stock} unit\n";
        }
        
        $message .= "🔗 Link: {$productUrl}";
        
        return $message;
    }
    
    /**
     * Check if product has valid WhatsApp contact
     */
    public static function hasValidWhatsappContact(Product $product)
    {
        return !empty($product->whatsapp_number) && self::formatPhoneNumber($product->whatsapp_number) !== null;
    }
    
    /**
     * Get formatted display phone number
     */
    public static function getDisplayPhoneNumber($phoneNumber)
    {
        if (!$phoneNumber) {
            return '-';
        }
        
        $phone = preg_replace('/\D/', '', $phoneNumber);
        
        // Format Indonesian numbers for display
        if (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        } elseif (str_starts_with($phone, '0')) {
            // Already in correct format
        } else {
            $phone = '0' . $phone;
        }
        
        // Add formatting
        if (strlen($phone) >= 10) {
            return preg_replace('/(\d{4})(\d{4})(\d+)/', '$1-$2-$3', $phone);
        }
        
        return $phone;
    }
}