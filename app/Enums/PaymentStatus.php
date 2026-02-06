<?php

namespace App\Enums;

/**
 * Payment Status Enum
 *
 * Standardized payment status untuk seluruh aplikasi.
 * Gunakan enum ini untuk consistency antara backend logic dan frontend display.
 */
class PaymentStatus
{
    // Standard payment statuses
    const UNPAID = 'unpaid';
    const PENDING = 'pending';
    const PAID = 'paid';
    const FAILED = 'failed';
    const CANCELLED = 'cancelled';

    // Installment-specific statuses
    const INSTALLMENT_ACTIVE = 'installment_active';
    const INSTALLMENT_OVERDUE = 'installment_overdue';
    const INSTALLMENT_COMPLETED = 'installment_completed';

    /**
     * Get all valid payment statuses
     */
    public static function all(): array
    {
        return [
            self::UNPAID,
            self::PENDING,
            self::PAID,
            self::FAILED,
            self::CANCELLED,
            self::INSTALLMENT_ACTIVE,
            self::INSTALLMENT_OVERDUE,
            self::INSTALLMENT_COMPLETED,
        ];
    }

    /**
     * Get human-readable label for payment status
     */
    public static function label(string $status): string
    {
        return match($status) {
            self::UNPAID => 'Belum Dibayar',
            self::PENDING => 'Menunggu Pembayaran',
            self::PAID => 'Lunas',
            self::FAILED => 'Pembayaran Gagal',
            self::CANCELLED => 'Dibatalkan',
            self::INSTALLMENT_ACTIVE => 'Cicilan Berjalan',
            self::INSTALLMENT_OVERDUE => 'Cicilan Terlambat',
            self::INSTALLMENT_COMPLETED => 'Cicilan Lunas',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    /**
     * Get badge color class for payment status
     */
    public static function badgeClass(string $status): string
    {
        return match($status) {
            self::PAID, self::INSTALLMENT_COMPLETED => 'bg-green-100 text-green-800',
            self::PENDING => 'bg-yellow-100 text-yellow-800',
            self::UNPAID => 'bg-gray-100 text-gray-800',
            self::FAILED, self::CANCELLED => 'bg-red-100 text-red-800',
            self::INSTALLMENT_ACTIVE => 'bg-blue-100 text-blue-800',
            self::INSTALLMENT_OVERDUE => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Check if payment is completed (paid or installment completed)
     */
    public static function isCompleted(string $status): bool
    {
        return in_array($status, [self::PAID, self::INSTALLMENT_COMPLETED]);
    }

    /**
     * Check if payment is pending or active
     */
    public static function isPending(string $status): bool
    {
        return in_array($status, [self::PENDING, self::UNPAID, self::INSTALLMENT_ACTIVE]);
    }

    /**
     * Check if payment failed or cancelled
     */
    public static function isFailed(string $status): bool
    {
        return in_array($status, [self::FAILED, self::CANCELLED]);
    }
}
