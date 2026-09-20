# سینماپلاس

سامانه حرفه‌ای فروش بلیت سینما و تئاتر برای شرکت خصوصی، با مدیریت سالن، نقشه صندلی، سانس، سفارش، بلیت QR، دسته‌بندی منعطف، نقش‌ها و مجوزها، رسانه و تاریخ شمسی.

## نصب
```bash
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

وابستگی‌ها: Laravel 13، Spatie Permission، Spatie Media Library، Byekan/Morilog Jalali و Kavenegar.

طرح‌های اولیه در `public/images/concepts` قرار دارند. کلید Kavenegar و تنظیمات درگاه پرداخت باید فقط در `.env` باشند.

دامنه‌های اصلی: `organizations`, `categories`, `venues`, `seats`, `events`, `shows`, `orders`, `tickets`.

برای فهرست کامل امکانات به [docs/FEATURES.md](docs/FEATURES.md) و برای نصب به [docs/INSTALLATION.md](docs/INSTALLATION.md) مراجعه کنید.

## اجرای production با Docker
```bash
docker compose up -d --build
docker compose exec app php artisan migrate --force --seed
docker compose logs -f worker
```
سرویس `worker` صف‌های `notifications` و `default` را از Redis مصرف می‌کند و Nginx به‌عنوان reverse proxy جلوی برنامه قرار دارد. برای HTTPS، گواهی را در لایهٔ دامنه/Load Balancer یا Nginx محیط production نصب کنید و `APP_URL` و callback زرین‌پال را روی دامنهٔ HTTPS تنظیم کنید.
