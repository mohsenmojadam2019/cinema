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

## پیامک

```env
KAVENEGAR_API_KEY=your-key
KAVENEGAR_SENDER=your-sender
```

کلیدها را commit نکنید.
