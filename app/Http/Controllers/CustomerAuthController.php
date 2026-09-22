<?php

namespace App\Http\Controllers;

use App\Jobs\SendSmsJob;
use App\Models\LoginCode;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class CustomerAuthController extends Controller
{
    public function show(): View
    {
        return view('auth.customer-otp', ['mode' => 'login']);
    }

    public function showRegister(): View
    {
        return view('auth.customer-otp', ['mode' => 'register']);
    }

    public function loginWithPassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $phone = $this->normalizeIranianMobile($data['phone']);

        if ($phone === null) {
            return back()->withErrors([
                'phone' => 'شماره موبایل معتبر وارد کنید.',
            ])->withInput($request->except('password'));
        }

        $rateLimitKey = 'customer-password:'.$phone.'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 8)) {
            return back()->withErrors([
                'phone' => 'تعداد تلاش‌های ورود زیاد است. کمی بعد دوباره امتحان کنید.',
            ])->withInput($request->except('password'));
        }

        if (! Auth::attempt(['phone' => $phone, 'password' => $data['password']], $request->boolean('remember'))) {
            RateLimiter::hit($rateLimitKey, 60);

            return back()->withErrors([
                'phone' => 'شماره موبایل یا رمز عبور صحیح نیست.',
            ])->withInput($request->except('password'));
        }

        RateLimiter::clear($rateLimitKey);
        $request->session()->regenerate();

        return redirect()->intended(route('account.orders'));
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:80'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'max:72', 'confirmed'],
        ]);

        $phone = $this->normalizeIranianMobile($data['phone']);

        if ($phone === null) {
            return back()->withErrors([
                'phone' => 'شماره موبایل معتبر وارد کنید.',
            ])->withInput($request->except(['password', 'password_confirmation']));
        }

        if (User::where('phone', $phone)->exists()) {
            return back()->withErrors([
                'phone' => 'این شماره قبلاً ثبت شده است. وارد حساب خود شوید یا از کد یکبارمصرف استفاده کنید.',
            ])->withInput($request->except(['password', 'password_confirmation']));
        }

        $rateLimitKey = 'customer-register:'.$phone.'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            return back()->withErrors([
                'phone' => 'تعداد درخواست‌های ثبت‌نام زیاد است. کمی بعد دوباره امتحان کنید.',
            ])->withInput($request->except(['password', 'password_confirmation']));
        }

        RateLimiter::hit($rateLimitKey, 60);

        $user = User::create([
            'name' => trim($data['name']),
            'phone' => $phone,
            'email' => $this->customerEmail($phone),
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user, true);
        RateLimiter::clear($rateLimitKey);
        $request->session()->regenerate();

        return redirect()
            ->route('account.orders')
            ->with('success', 'حساب کاربری شما با موفقیت ساخته شد.');
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $phone = $this->normalizeIranianMobile($data['phone']);

        if ($phone === null) {
            return back()->withErrors([
                'phone' => 'شماره موبایل معتبر وارد کنید.',
            ])->withInput();
        }

        $rateLimitKey = 'otp:send:'.$phone.'|'.$request->ip();

        abort_if(
            RateLimiter::tooManyAttempts($rateLimitKey, 6),
            429,
            'تعداد درخواست‌ها زیاد است.'
        );

        RateLimiter::hit($rateLimitKey, 60);
        $code = (string) random_int(100000, 999999);

        LoginCode::where('phone', $phone)
            ->whereNull('used_at')
            ->delete();

        LoginCode::create([
            'phone' => $phone,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(5),
        ]);

        SendSmsJob::dispatch($phone, 'کد ورود سینماپلاس: '.$code)
            ->onQueue('notifications');

        return back()
            ->with('phone', $phone)
            ->with('success', 'کد ورود ارسال شد.');
    }

    public function verify(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'code' => ['required', 'digits:6'],
        ]);

        $phone = $this->normalizeIranianMobile($data['phone']);

        if ($phone === null) {
            return back()->withErrors([
                'phone' => 'شماره موبایل معتبر وارد کنید.',
            ])->withInput();
        }

        $rateLimitKey = 'otp:verify:'.$phone.'|'.$request->ip();

        abort_if(
            RateLimiter::tooManyAttempts($rateLimitKey, 10),
            429,
            'تعداد تلاش‌ها زیاد است.'
        );

        RateLimiter::hit($rateLimitKey, 60);

        $loginCode = LoginCode::where('phone', $phone)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        abort_unless(
            $loginCode && $loginCode->attempts < 5,
            422,
            'کد منقضی شده است.'
        );

        if (! Hash::check($data['code'], $loginCode->code_hash)) {
            $loginCode->increment('attempts');

            return back()
                ->withErrors(['code' => 'کد صحیح نیست.'])
                ->withInput();
        }

        $loginCode->update(['used_at' => now()]);

        $user = User::firstOrCreate(
            ['phone' => $phone],
            [
                'name' => 'مشتری',
                'email' => $this->customerEmail($phone),
                'password' => Hash::make(str()->random(40)),
            ]
        );

        Auth::login($user, true);
        RateLimiter::clear($rateLimitKey);
        $request->session()->regenerate();

        return redirect()->intended(route('account.orders'));
    }

    private function normalizeIranianMobile(string $value): ?string
    {
        $value = strtr(trim($value), [
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
            '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        ]);

        $value = preg_replace('/[^0-9+]/', '', $value) ?? '';

        if (str_starts_with($value, '+98')) {
            $value = '0'.substr($value, 3);
        } elseif (str_starts_with($value, '0098')) {
            $value = '0'.substr($value, 4);
        } elseif (str_starts_with($value, '98') && strlen($value) === 12) {
            $value = '0'.substr($value, 2);
        }

        return preg_match('/^09\d{9}$/', $value) === 1 ? $value : null;
    }

    private function customerEmail(string $phone): string
    {
        return 'customer+'.preg_replace('/\D/', '', $phone).'@cinemaplus.local';
    }
}
