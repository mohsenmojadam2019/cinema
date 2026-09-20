# راهنمای استقرار Production

## سرویس‌ها

- `app`: Laravel application
- `worker`: مصرف صف‌های Redis با queueهای `notifications,default`
- `scheduler`: اجرای آزادسازی رزروها و backupهای زمان‌بندی‌شده
- `redis`: cache و queue broker
- `nginx`: reverse proxy روی پورت 80

```bash
cp .env.example .env
php artisan key:generate
docker compose up -d --build
docker compose exec app php artisan migrate --force --seed
docker compose exec app php artisan storage:link
```

## تنظیمات ضروری `.env`

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://cinema.example.ir
QUEUE_CONNECTION=redis
CACHE_STORE=redis
REDIS_HOST=redis
SMS_DRIVER=kavenegar
KAVENEGAR_API_KEY=توسط مدیر سیستم تنظیم شود
KAVENEGAR_SENDER=توسط مدیر سیستم تنظیم شود
PAYMENT_DRIVER=zarinpal
ZARINPAL_SANDBOX=false
ZARINPAL_MERCHANT_ID=توسط مدیر سیستم تنظیم شود
ZARINPAL_CALLBACK_URL=https://cinema.example.ir/payment/callback
```

کلیدها نباید در Git، Seeder یا Docker image قرار بگیرند.

## HTTPS و دامنه

Nginx داخلی، ترافیک را به سرویس `app` proxy می‌کند. در محیط واقعی، TLS را روی Nginx مرزی یا Load Balancer نصب کنید، گواهی را با renewal خودکار (برای مثال Certbot) تمدید کنید و callback زرین‌پال را دقیقاً روی URL HTTPS ثبت کنید.

## پایش و سلامت

- health endpoint لاراول: `/health` و `/up`
- health command: `php artisan cinema:health`
- لاگ: `docker compose logs -f app worker scheduler nginx`
- خطاهای صف: `php artisan queue:failed`
- اجرای worker باید با restart policy یا Supervisor/سرویس orchestration مدیریت شود.

## پشتیبان‌گیری و بازیابی

```bash
docker compose exec app php artisan cinema:backup
docker compose exec app php artisan migrate:status
```

فایل‌های قدیمی‌تر از `BACKUP_RETENTION_DAYS` خودکار حذف می‌شوند. قبل از release، یک restore آزمایشی روی محیط جداگانه انجام دهید و فایل‌های `storage` و دیتابیس را هر دو backup کنید. برای SQLite:

```bash
docker compose exec app sh -lc 'cp storage/backups/cinema-YYYYMMDD-HHMMSS.sqlite database/restore-test.sqlite && sqlite3 database/restore-test.sqlite "PRAGMA integrity_check;"'
```
