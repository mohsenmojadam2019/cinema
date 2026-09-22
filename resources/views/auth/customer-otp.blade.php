<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $mode === 'register' ? 'ثبت‌نام' : 'ورود' }} | سینماپلاس</title>
    <link rel="stylesheet" href="/css/cinema.css?v=20260922-auth">
</head>
<body class="cinema-auth-page">
<main class="cinema-auth-shell">
    <section class="cinema-auth-card">
        <a class="cinema-auth-brand" href="{{ route('home') }}">
            <img src="/images/branding/cinemaplus-logo.png" alt="سینماپلاس">
            <span>سینما<span class="brand-accent">پلاس</span></span>
        </a>

        <div class="auth-kicker">
            <span class="auth-kicker-dot"></span>
            تجربه امن خرید بلیت
        </div>

        <nav class="auth-switcher" aria-label="ورود و ثبت‌نام">
            <a class="{{ $mode === 'login' ? 'active' : '' }}" href="{{ route('customer.login') }}">ورود</a>
            <a class="{{ $mode === 'register' ? 'active' : '' }}" href="{{ route('customer.register') }}">ثبت‌نام</a>
        </nav>

        @if(session('success'))
            <div class="auth-alert auth-alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="auth-alert auth-alert-error">{{ $errors->first() }}</div>
        @endif

        @if($mode === 'register')
            <div class="auth-heading">
                <span>عضویت در سینماپلاس</span>
                <h1>حساب کاربری بسازید</h1>
                <p>برای رزرو سریع، پیگیری بلیت‌ها و خریدهای بعدی ثبت‌نام کنید.</p>
            </div>

            <form class="auth-form" method="post" action="{{ route('customer.register.store') }}">
                @csrf
                <label>
                    <span>نام و نام خانوادگی</span>
                    <input class="auth-input" name="name" value="{{ old('name') }}" autocomplete="name" placeholder="مثلاً علی رضایی" required>
                </label>
                <label>
                    <span>شماره موبایل</span>
                    <input class="auth-input" name="phone" value="{{ old('phone') }}" inputmode="tel" autocomplete="tel" placeholder="۰۹۱۲۱۲۳۴۵۶۷" required>
                </label>
                <div class="auth-password-grid">
                    <label>
                        <span>رمز عبور</span>
                        <input class="auth-input" name="password" type="password" autocomplete="new-password" placeholder="حداقل ۸ کاراکتر" required>
                    </label>
                    <label>
                        <span>تکرار رمز عبور</span>
                        <input class="auth-input" name="password_confirmation" type="password" autocomplete="new-password" placeholder="رمز را دوباره وارد کنید" required>
                    </label>
                </div>

                <button class="auth-primary" type="submit">ساخت حساب کاربری</button>
                <p class="auth-legal">با ثبت‌نام، اطلاعات حساب شما فقط برای ارائه خدمات سینماپلاس استفاده می‌شود.</p>
            </form>
        @else
            <div class="auth-heading">
                <span>خوش آمدید</span>
                <h1>ورود به حساب کاربری</h1>
                <p>با شماره موبایل و رمز عبور وارد شوید یا از کد یکبارمصرف استفاده کنید.</p>
            </div>

            <form class="auth-form" method="post" action="{{ route('customer.login.password') }}">
                @csrf
                <label>
                    <span>شماره موبایل</span>
                    <input class="auth-input" name="phone" value="{{ old('phone', session('phone')) }}" inputmode="tel" autocomplete="tel" placeholder="۰۹۱۲۱۲۳۴۵۶۷" required>
                </label>
                <label>
                    <span>رمز عبور</span>
                    <input class="auth-input" name="password" type="password" autocomplete="current-password" placeholder="رمز عبور شما" required>
                </label>

                <label class="auth-remember">
                    <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    <span>مرا به خاطر بسپار</span>
                </label>

                <button class="auth-primary" type="submit">ورود به سینماپلاس</button>
            </form>

            <div class="auth-divider"><span>یا</span></div>

            <details class="auth-otp" {{ session('phone') ? 'open' : '' }}>
                <summary>
                    <span>
                        <strong>ورود با کد یکبارمصرف</strong>
                        <small>بدون نیاز به رمز عبور</small>
                    </span>
                    <b>＋</b>
                </summary>

                <div class="auth-otp-body">
                    <form class="auth-form compact" method="post" action="{{ route('customer.login.send') }}">
                        @csrf
                        <label>
                            <span>شماره موبایل</span>
                            <input class="auth-input" name="phone" value="{{ old('phone', session('phone')) }}" inputmode="tel" placeholder="۰۹۱۲۱۲۳۴۵۶۷" required>
                        </label>
                        <button class="auth-secondary" type="submit">ارسال کد ورود</button>
                    </form>

                    @if(session('phone'))
                        <form class="auth-form compact otp-verify-form" method="post" action="{{ route('customer.login.verify') }}">
                            @csrf
                            <label>
                                <span>کد شش رقمی</span>
                                <input class="auth-input auth-code" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" placeholder="••••••" required>
                            </label>
                            <input type="hidden" name="phone" value="{{ session('phone') }}">
                            <button class="auth-secondary" type="submit">تأیید کد و ورود</button>
                        </form>
                    @endif
                </div>
            </details>
        @endif

        <a class="auth-home-link" href="{{ route('home') }}">بازگشت به صفحه اصلی ←</a>
    </section>

    <aside class="cinema-auth-visual" aria-hidden="true">
        <div class="cinema-visual-copy">
            <span class="cinema-premiere">CINEMA+ PREMIERE</span>
            <h2>هر بلیت،<br>شروع یک داستان تازه.</h2>
            <p>اکران‌های تازه، انتخاب صندلی و بلیت‌های شما در یک تجربه سریع و سینمایی.</p>
        </div>

        <div class="cinema-poster-wall">
            <span class="cinema-mini-poster poster-one"></span>
            <span class="cinema-mini-poster poster-two"></span>
            <span class="cinema-mini-poster poster-three"></span>
            <span class="cinema-mini-poster poster-four"></span>
            <span class="cinema-mini-poster poster-five"></span>
            <span class="cinema-mini-poster poster-six"></span>
        </div>

        <div class="cinema-visual-meta">
            <span><b>۲۰+</b> اثر منتخب</span>
            <span><b>آنلاین</b> رزرو صندلی</span>
            <span><b>امن</b> پرداخت و بلیت</span>
        </div>
    </aside>
</main>
</body>
</html>
