<?php

namespace App\Services;

use App\Models\Product\Product;
use App\Models\Supplier;
use App\Models\Inventory\InventoryMovement;
use App\Models\Finance\FinancialTransaction;
use App\Models\Finance\TransactionCategory;
use Illuminate\Support\Facades\Log;

class WhatsAppInventoryService
{
    /**
     * Process incoming WhatsApp message for inventory management
     *
     * @param string $message
     * @param string $senderPhone
     * @return array ['success' => bool, 'message' => string]
     */
    public function processMessage($message, $senderPhone)
    {
        try {
            // Verify sender is authorized admin
            if (!$this->isAuthorizedAdmin($senderPhone)) {
                return [
                    'success' => false,
                    'message' => "⛔ Nomor tidak terdaftar sebagai admin. Hubungi SuperAdmin untuk mendaftar."
                ];
            }

            // Parse message
            $command = $this->parseMessage($message);

            if (!$command) {
                return [
                    'success' => false,
                    'message' => $this->getHelpMessage()
                ];
            }

            // Process command
            return $this->executeCommand($command, $senderPhone);

        } catch (\Exception $e) {
            Log::error('WhatsApp Inventory Error', [
                'message' => $message,
                'sender' => $senderPhone,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => "❌ Terjadi kesalahan: " . $e->getMessage()
            ];
        }
    }

    /**
     * Parse WhatsApp message into command array
     *
     * Format 1 (Simple):
     * MASUK <kode_produk> <qty> <harga> <kode_supplier>
     * Example: MASUK BR5KG 100 50000 SUP001
     *
     * Format 2 (Detail):
     * MASUK
     * Produk: BR5KG
     * Qty: 100
     * Harga: 50000
     * Supplier: SUP001
     * Notes: PO2026-001
     */
    private function parseMessage($message)
    {
        $message = trim($message);
        $lines = explode("\n", $message);
        $firstLine = strtoupper(trim($lines[0]));

        // Check command type
        if (strpos($firstLine, 'MASUK') === 0) {
            return $this->parseStockInCommand($message, $lines, $firstLine);
        } elseif (strpos($firstLine, 'KELUAR') === 0) {
            return $this->parseStockOutCommand($message, $lines, $firstLine);
        } elseif (strpos($firstLine, 'HELP') === 0 || strpos($firstLine, 'BANTUAN') === 0) {
            return null; // Will show help message
        }

        return null;
    }

    /**
     * Parse MASUK (Stock In) command
     */
    private function parseStockInCommand($message, $lines, $firstLine)
    {
        // Try simple format first: MASUK BR5KG 100 50000 SUP001
        $parts = preg_split('/\s+/', $firstLine);

        if (count($parts) >= 4) {
            return [
                'type' => 'stock_in',
                'product_code' => $parts[1],
                'quantity' => (int) $parts[2],
                'unit_price' => (float) $parts[3],
                'supplier_code' => $parts[4] ?? null,
                'notes' => null
            ];
        }

        // Try detailed format
        if (count($lines) > 1) {
            $data = ['type' => 'stock_in'];
            foreach ($lines as $line) {
                if (stripos($line, 'Produk:') !== false) {
                    $data['product_code'] = trim(explode(':', $line)[1]);
                } elseif (stripos($line, 'Qty:') !== false) {
                    $data['quantity'] = (int) trim(explode(':', $line)[1]);
                } elseif (stripos($line, 'Harga:') !== false) {
                    $data['unit_price'] = (float) trim(explode(':', $line)[1]);
                } elseif (stripos($line, 'Supplier:') !== false) {
                    $data['supplier_code'] = trim(explode(':', $line)[1]);
                } elseif (stripos($line, 'Notes:') !== false || stripos($line, 'Keterangan:') !== false) {
                    $data['notes'] = trim(explode(':', $line)[1]);
                }
            }

            if (isset($data['product_code']) && isset($data['quantity'])) {
                return $data;
            }
        }

        return null;
    }

    /**
     * Parse KELUAR (Stock Out) command
     */
    private function parseStockOutCommand($message, $lines, $firstLine)
    {
        // Try simple format: KELUAR BR5KG 50 Rusak
        $parts = preg_split('/\s+/', $firstLine, 4);

        if (count($parts) >= 3) {
            return [
                'type' => 'stock_out',
                'product_code' => $parts[1],
                'quantity' => (int) $parts[2],
                'notes' => $parts[3] ?? 'Stock out manual'
            ];
        }

        // Try detailed format
        if (count($lines) > 1) {
            $data = ['type' => 'stock_out'];
            foreach ($lines as $line) {
                if (stripos($line, 'Produk:') !== false) {
                    $data['product_code'] = trim(explode(':', $line)[1]);
                } elseif (stripos($line, 'Qty:') !== false) {
                    $data['quantity'] = (int) trim(explode(':', $line)[1]);
                } elseif (stripos($line, 'Notes:') !== false || stripos($line, 'Keterangan:') !== false) {
                    $data['notes'] = trim(explode(':', $line)[1]);
                }
            }

            if (isset($data['product_code']) && isset($data['quantity'])) {
                return $data;
            }
        }

        return null;
    }

    /**
     * Execute parsed command
     */
    private function executeCommand($command, $senderPhone)
    {
        if ($command['type'] === 'stock_in') {
            return $this->processStockIn($command, $senderPhone);
        } elseif ($command['type'] === 'stock_out') {
            return $this->processStockOut($command, $senderPhone);
        }

        return [
            'success' => false,
            'message' => "❌ Command tidak dikenali"
        ];
    }

    /**
     * Process Stock In
     */
    private function processStockIn($command, $senderPhone)
    {
        // Find product by code or SKU
        $product = Product::where('sku', $command['product_code'])
            ->orWhere('name', 'like', '%' . $command['product_code'] . '%')
            ->first();

        if (!$product) {
            return [
                'success' => false,
                'message' => "❌ Produk dengan kode '{$command['product_code']}' tidak ditemukan"
            ];
        }

        // Find supplier if provided
        $supplier = null;
        if (!empty($command['supplier_code'])) {
            $supplier = Supplier::where('code', $command['supplier_code'])->first();
        }

        $quantity = $command['quantity'];
        $unitPrice = $command['unit_price'] ?? 0;
        $totalPrice = $quantity * $unitPrice;

        // Update product stock
        $product->increment('stock', $quantity);

        // Record inventory movement
        $additionalData = [];
        if ($supplier) {
            $additionalData['supplier_id'] = $supplier->id;
        }
        if (!empty($command['notes'])) {
            $additionalData['document_number'] = $command['notes'];
        }
        if ($unitPrice > 0) {
            $additionalData['unit_price'] = $unitPrice;
            $additionalData['total_price'] = $totalPrice;
        }

        $movement = InventoryMovement::record(
            $product->id,
            'in',
            $quantity,
            null,
            "Stock in via WhatsApp dari {$senderPhone}",
            $additionalData
        );

        // Record financial transaction if price provided
        if ($unitPrice > 0) {
            $category = TransactionCategory::where('name', 'Pembelian Barang')->first();
            if ($category) {
                FinancialTransaction::create([
                    'transaction_number' => FinancialTransaction::generateTransactionNumber(),
                    'type' => 'expense',
                    'category_id' => $category->id,
                    'transaction_date' => now(),
                    'amount' => $totalPrice,
                    'description' => "Pembelian {$product->name} via WhatsApp",
                    'notes' => $command['notes'] ?? "Stock in dari WhatsApp",
                    'reference_type' => get_class($movement),
                    'reference_id' => $movement->id,
                    'payment_method' => 'Transfer',
                    'created_by' => $this->getAdminIdByPhone($senderPhone)
                ]);
            }
        }

        $response = "✅ *STOCK MASUK BERHASIL*\n\n";
        $response .= "📦 Produk: {$product->name}\n";
        $response .= "➕ Qty: {$quantity}\n";
        $response .= "📊 Stock Sebelum: " . ($product->stock - $quantity) . "\n";
        $response .= "📊 Stock Sekarang: {$product->stock}\n";
        if ($unitPrice > 0) {
            $response .= "💰 Harga: Rp " . number_format($unitPrice, 0, ',', '.') . "\n";
            $response .= "💵 Total: Rp " . number_format($totalPrice, 0, ',', '.') . "\n";
        }
        if ($supplier) {
            $response .= "🏪 Supplier: {$supplier->name}\n";
        }
        $response .= "\n📝 Keterangan: " . ($command['notes'] ?? '-');

        return [
            'success' => true,
            'message' => $response
        ];
    }

    /**
     * Process Stock Out
     */
    private function processStockOut($command, $senderPhone)
    {
        // Find product
        $product = Product::where('sku', $command['product_code'])
            ->orWhere('name', 'like', '%' . $command['product_code'] . '%')
            ->first();

        if (!$product) {
            return [
                'success' => false,
                'message' => "❌ Produk dengan kode '{$command['product_code']}' tidak ditemukan"
            ];
        }

        $quantity = $command['quantity'];

        // Check stock availability
        if ($product->stock < $quantity) {
            return [
                'success' => false,
                'message' => "❌ Stock tidak cukup!\n\n📦 {$product->name}\n📊 Stock: {$product->stock}\n❌ Diminta: {$quantity}"
            ];
        }

        // Update product stock
        $product->decrement('stock', $quantity);

        // Record inventory movement
        $movement = InventoryMovement::record(
            $product->id,
            'out',
            $quantity,
            null,
            "Stock out via WhatsApp: " . ($command['notes'] ?? 'Manual adjustment')
        );

        $response = "✅ *STOCK KELUAR BERHASIL*\n\n";
        $response .= "📦 Produk: {$product->name}\n";
        $response .= "➖ Qty: {$quantity}\n";
        $response .= "📊 Stock Sebelum: " . ($product->stock + $quantity) . "\n";
        $response .= "📊 Stock Sekarang: {$product->stock}\n";
        $response .= "\n📝 Keterangan: " . ($command['notes'] ?? '-');

        return [
            'success' => true,
            'message' => $response
        ];
    }

    /**
     * Check if phone number is authorized admin
     */
    private function isAuthorizedAdmin($phone)
    {
        // Remove +62, 62, 0 prefix and clean
        $cleanPhone = preg_replace('/^(\+62|62|0)/', '', $phone);
        $cleanPhone = preg_replace('/[^0-9]/', '', $cleanPhone);

        // Check if user exists with this phone and is admin
        $user = \App\Models\User::whereIn('role', ['admin', 'superadmin'])
            ->where(function($q) use ($cleanPhone) {
                $q->where('phone', 'like', '%' . $cleanPhone . '%')
                  ->orWhere('phone', 'like', '%0' . $cleanPhone . '%')
                  ->orWhere('phone', 'like', '%+62' . $cleanPhone . '%');
            })
            ->first();

        return $user !== null;
    }

    /**
     * Get admin ID by phone
     */
    private function getAdminIdByPhone($phone)
    {
        $cleanPhone = preg_replace('/^(\+62|62|0)/', '', $phone);
        $cleanPhone = preg_replace('/[^0-9]/', '', $cleanPhone);

        $user = \App\Models\User::whereIn('role', ['admin', 'superadmin'])
            ->where(function($q) use ($cleanPhone) {
                $q->where('phone', 'like', '%' . $cleanPhone . '%')
                  ->orWhere('phone', 'like', '%0' . $cleanPhone . '%')
                  ->orWhere('phone', 'like', '%+62' . $cleanPhone . '%');
            })
            ->first();

        return $user ? $user->id : 1; // Default to superadmin if not found
    }

    /**
     * Get help message
     */
    private function getHelpMessage()
    {
        return "*📚 PANDUAN WHATSAPP INVENTORY*\n\n" .
               "*1️⃣ STOCK MASUK (Simple):*\n" .
               "`MASUK <kode> <qty> <harga> <supplier>`\n" .
               "Contoh:\n`MASUK BR5KG 100 50000 SUP001`\n\n" .

               "*2️⃣ STOCK MASUK (Detail):*\n" .
               "```MASUK\nProduk: BR5KG\nQty: 100\nHarga: 50000\nSupplier: SUP001\nNotes: PO2026-001```\n\n" .

               "*3️⃣ STOCK KELUAR (Simple):*\n" .
               "`KELUAR <kode> <qty> <keterangan>`\n" .
               "Contoh:\n`KELUAR BR5KG 50 Rusak`\n\n" .

               "*4️⃣ STOCK KELUAR (Detail):*\n" .
               "```KELUAR\nProduk: BR5KG\nQty: 50\nNotes: Barang rusak```\n\n" .

               "💡 *Tips:*\n" .
               "- Kode produk bisa SKU atau nama\n" .
               "- Supplier code opsional\n" .
               "- Harga opsional (untuk stock in)\n" .
               "- Gunakan HELP untuk melihat panduan ini";
    }
}
