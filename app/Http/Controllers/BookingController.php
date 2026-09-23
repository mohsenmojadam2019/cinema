<?php

namespace App\Http\Controllers;

use App\Jobs\SendSmsJob;
use App\Models\Event;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Seat;
use App\Models\Show;
use App\Models\Ticket;
use App\Services\DiscountService;
use App\Services\ZarinpalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function event(Event $event)
    {
        return view('events.show', ['event' => $event->load('category', 'shows.venue'), 'shows' => $event->shows()->with('venue')->where('status', 'on_sale')->where('starts_at', '>', now())->orderBy('starts_at')->get()]);
    }

    public function seats(Show $show)
    {
        $show->load('event', 'venue');
        Reservation::where('show_id', $show->id)->where('status', 'held')->where('expires_at', '<=', now())->update(['status' => 'expired']);
        $taken = Ticket::whereHas('order', fn ($q) => $q->where('show_id', $show->id)->whereIn('status', ['paid', 'valid']))->pluck('seat_id');
        $held = Reservation::active()->where('show_id', $show->id)->pluck('seat_id');

        return view('booking.seats', ['show' => $show, 'seats' => $show->venue->seats()->where('is_active', true)->orderBy('row_label')->orderBy('number')->get(), 'taken' => $taken->merge($held)]);
    }

    public function checkout(Request $request, Show $show, DiscountService $discounts)
    {
        if (! auth()->check()) {
            return redirect()->route('customer.login')->with('error', 'برای خرید ابتدا وارد حساب مشتری شوید.');
        }$data = $request->validate(['seats' => 'required|array|min:1', 'seats.*' => 'integer|exists:seats,id', 'coupon_code' => 'nullable|string|max:40']);
        $show->load('venue');
        $order = null;
        $coupon = null;
        DB::transaction(function () use ($data, $show, &$order) {
            $seats = Seat::whereIn('id', $data['seats'])->where('venue_id', $show->venue_id)->lockForUpdate()->get();
            if ($seats->count() !== count($data['seats'])) {
                abort(422, 'صندلی نامعتبر است.');
            }$busy = Ticket::whereIn('seat_id', $seats->pluck('id'))->whereHas('order', fn ($q) => $q->where('show_id', $show->id)->whereIn('status', ['paid', 'valid']))->exists() || Reservation::active()->where('show_id', $show->id)->whereIn('seat_id', $seats->pluck('id'))->exists();
            if ($busy) {
                abort(409, 'یکی از صندلی‌ها قبلاً رزرو شده است.');
            }$order = Order::create(['user_id' => auth()->id(), 'show_id' => $show->id, 'code' => 'CP-'.strtoupper(Str::random(10)), 'total' => $seats->sum(fn ($s) => $s->type === 'vip' && $show->vip_price ? $show->vip_price : $show->price), 'status' => 'pending', 'expires_at' => now()->addMinutes(10)]);
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
        $useGateway = config('services.payment.driver') === 'zarinpal'
            && filled($merchant)
            && ! str_contains($merchant, 'xxxx');
        if ($useGateway) {
            $payment = app(ZarinpalService::class)->request($order);

            return redirect()->away(app(ZarinpalService::class)->url($payment));
        }$order->update(['status' => 'paid', 'paid_at' => now()]);
        Reservation::where('token', $order->code)->update(['status' => 'converted']);
        Ticket::where('order_id', $order->id)->update(['status' => 'valid']);
        if (auth()->user()?->phone) {
            SendSmsJob::dispatch(auth()->user()->phone, 'خرید سفارش '.$order->code.' با موفقیت ثبت شد.')->onQueue('notifications');
        }

        return redirect()->route('booking.success', $order);
    }

    public function success(Order $order)
    {
        abort_unless(auth()->check() && ($order->user_id === auth()->id() || auth()->user()->hasAnyRole(['مدیر سیستم', 'مدیر فروش', 'اپراتور گیشه'])), 403);

        return view('booking.success', ['order' => $order->load('show.event', 'show.venue', 'tickets.seat')]);
    }
}
