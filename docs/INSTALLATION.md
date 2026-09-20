# نصب و راه‌اندازی

## توسعه محلی

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

ورود مدیر نمونه:

```text
email: admin@cinemaplus.test
password: ChangeMe123!
```

این رمز فقط برای توسعه است و باید در محیط واقعی تغییر کند.

## Docker

```bash
docker compose up --build
```

سپس به `http://localhost:8000` بروید.

## بررسی سلامت

```bash
php artisan cinema:health
```

## پیامک

```env
KAVENEGAR_API_KEY=your-key
KAVENEGAR_SENDER=your-sender
```

کلیدها را commit نکنید.

## درگاه پرداخت

در پروژه قبلی secret واقعی Kavenegar یا درگاه پرداخت پیدا نشد؛ بنابراین مقدار جعلی وارد نشده است. برای فعال‌سازی، مقادیر واقعی را فقط در `.env` قرار دهید:

```env
PAYMENT_DRIVER=your-gateway
PAYMENT_MERCHANT_ID=your-merchant-id
PAYMENT_CALLBACK_URL=https://your-domain.example/payment/callback
```

حالت `mock` فقط برای توسعه است. در محیط واقعی adapter درگاه باید callback، امضای درخواست، تطبیق مبلغ و idempotency را بررسی کند.
