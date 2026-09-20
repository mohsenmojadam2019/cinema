# راهنمای API موبایل

Base URL: `/api` — پاسخ‌ها JSON هستند.

## احراز هویت

`POST /auth/login`

```json
{"phone":"09120000000","code":"123456"}
```

پاسخ موفق شامل `token` است. آن را در هدر زیر بفرستید:

```text
Authorization: Bearer {token}
```

`GET /auth/me`، `POST /auth/logout`

## کاتالوگ عمومی

- `GET /events?q=&type=&category=&city=&date=` فیلتر رویدادها
- `GET /events/{event}` جزئیات رویداد و سانس‌ها
- `GET /shows/{show}` جزئیات سانس و صندلی‌ها

## خرید

`POST /shows/{show}/checkout` با احراز هویت:

```json
{"seats":[12,13]}
```

درایور mock پاسخ `201` و سفارش پرداخت‌شده می‌دهد؛ زرین‌پال پاسخ `202` و `payment_url` می‌دهد. رزرو هم‌زمان با قفل تراکنشی کنترل می‌شود و تعارض، `409` برمی‌گرداند.

## حساب مشتری

- `GET /account/orders`
- `GET /account/tickets`

## کنترل بلیت

`POST /tickets/{ticket}/checkin` علاوه بر Sanctum token به نقش `مدیر سیستم` یا `اپراتور گیشه` نیاز دارد. بلیت معتبر به وضعیت `used` می‌رود و استفادهٔ دوباره `422` است.

تمام endpointهای احراز‌شده rate limit شصت درخواست در دقیقه دارند. کلیدها و merchant در `.env` هستند و هرگز داخل repository قرار نمی‌گیرند.
