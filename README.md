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
