<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendSmsJob;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Seat;
use App\Models\Show;
use App\Models\Ticket;
use App\Services\DiscountService;
use App\Services\ZarinpalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingApiController extends Controller
{
    public function checkout(Request $r, Show $show, DiscountService $discounts): JsonResponse
    {
        $data = $r->validate(['seats' => 'required|array|min:1', 'seats.*' => 'integer|exists:seats,id', 'coupon_code' => 'nullable|string|max:40']);
        $show->load('venue');
        $order = null;
        DB::transaction(function () use ($data, $show, &$order, $r) {
            $seats = Seat::whereIn('id', $data['seats'])->where('venue_id', $show->venue_id)->lockForUpdate()->get();
            if ($seats->count() !== count($data['seats'])) {
                abort(422, 'صندلی نامعتبر است.');
            }$busy = Ticket::whereIn('seat_id', $seats->pluck('id'))->whereHas('order', fn ($q) => $q->where('show_id', $show->id)->whereIn('status', ['paid', 'valid']))->exists() || Reservation::active()->where('show_id', $show->id)->whereIn('seat_id', $seats->pluck('id'))->exists();
            if ($busy) {
                abort(409, 'یکی از صندلی‌ها قبلاً رزرو شده است.');
            }$order = Order::create(['user_id' => $r->user()->id, 'show_id' => $show->id, 'code' => 'API-'.strtoupper(Str::random(10)), 'total' => $seats->sum(fn ($s) => $s->type === 'vip' && $show->vip_price ? $show->vip_price : $show->price), 'status' => 'pending', 'expires_at' => now()->addMinutes(10)]);
            foreach ($seats as $seat) {
                Reservation::create(['show_id' => $show->id, 'seat_id' => $seat->id, 'token' => $order->code, 'expires_at' => now()->addMinutes(10)]);
                Ticket::create(['order_id' => $order->id, 'seat_id' => $seat->id, 'code' => 'T-'.strtoupper(Str::random(12)), 'status' => 'pending']);
            }
        });
        [$finalTotal, $coupon] = $discounts->apply($data['coupon_code'] ?? null, (int) $order->total);
        if ($coupon) {
            $order->update(['total' => $finalTotal]);
            $coupon->increment('used_count');
        }
        $merchant = (string) config('services.zarinpal.merchant_id');
        $useGateway = config('services.payment.driver') === 'zarinpal' && filled($merchant) && ! str_contains($merchant, 'xxxx');
        if ($useGateway) {
            $payment = app(ZarinpalService::class)->request($order);

            return response()->json(['order' => $order, 'payment_url' => app(ZarinpalService::class)->url($payment)], 202);
        } $order->update(['status' => 'paid', 'paid_at' => now()]);
        $r->user()?->increment('loyalty_points', max(1, intdiv((int) $order->total, 100000)));
        Reservation::where('token', $order->code)->update(['status' => 'converted']);
        Ticket::where('order_id', $order->id)->update(['status' => 'valid']);
        if ($r->user()?->phone) {
            SendSmsJob::dispatch($r->user()->phone, 'خرید سفارش '.$order->code.' با موفقیت ثبت شد.')->onQueue('notifications');
        }

        return response()->json($order->load('tickets.seat', 'show.event'), 201);
    }
}
