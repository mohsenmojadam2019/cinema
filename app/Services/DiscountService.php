<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Validation\ValidationException;

class DiscountService
{
    public function apply(?string $code, int $amount): array
    {
        if (! $code) {
            return [$amount, null];
        }
        $coupon = Coupon::where('code', strtoupper(trim($code)))->where('is_active', true)->first();
        if (! $coupon || ($coupon->starts_at && $coupon->starts_at->isFuture()) || ($coupon->ends_at && $coupon->ends_at->isPast()) || ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit)) {
            throw ValidationException::withMessages(['coupon_code' => 'کد تخفیف معتبر یا قابل استفاده نیست.']);
        }
        $discount = $coupon->type === 'percent' ? (int) floor($amount * min($coupon->value, 100) / 100) : min($coupon->value, $amount);

        return [$amount - $discount, $coupon];
    }
}
