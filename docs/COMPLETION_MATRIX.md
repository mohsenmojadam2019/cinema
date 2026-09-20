# ماتریس وضعیت قابلیت‌ها

| مورد | وضعیت | شواهد فعلی |
|---|---|---|
| CRUD رویداد، سالن، صندلی و سانس | آماده | Controllerهای Admin، پنل صندلی و routeهای edit/update |
| رزرو سازمانی و پیش‌فاکتور | آماده | `OrganizationBookingController` و migrationهای 140000/141000 |
| کنسرت و دسته‌بندی | آماده | category `singer-concerts` و type `concert` |
| OTP صفی | آماده | `SendSmsJob`، Redis worker، RateLimiter |
| SMS خرید/پرداخت/استرداد | آمادهٔ کدی | Jobهای پرداخت و refund؛ اجرای Kavenegar نیازمند کلید واقعی |
| ZarinPal Sandbox | تست قراردادی | `ZarinpalServiceTest`; اجرای live نیازمند merchant واقعی |
| refund idempotent | آماده | کنترل status و transaction در AdminReportController |
| تسویه برگزارکننده | آماده | Settlement model، migration و پنل `/admin/settlements` |
| رزرو هم‌زمان | تست‌شده | `lockForUpdate` و Feature test رزرو تکراری |
| callback/refund/OTP/QR/check-in | تست‌شدهٔ بخشی | ۱۱ تست Feature و ZarinPal contract tests؛ تست live gateway نیازمند Sandbox credential است |
| API موبایل | آماده | Sanctum auth، catalog، account و checkout routes |
| محدودسازی QR/API | آماده | auth:sanctum و owner/operator authorization |
| Media conversion | آمادهٔ صفی | queued Media Library conversion |
| Redis/Queue production | آماده | Docker services `redis`, `worker`, `scheduler` |
| HTTPS/Nginx | template آماده | `docker/nginx-production.conf`; گواهی واقعی بیرون Git |
| Backup و scheduler | آماده | `cinema:backup` و `schedule:work` |
| تصاویر اختصاصی ۲۰ فیلم | آماده | ۲۰ فایل PNG مستقل در `public/images/posters`؛ ۱۲ asset طراحی‌شده و ۸ asset قابل‌بازتولید با `php artisan cinema:posters` و متصل به Seeder |
| UX موبایل | پایه آماده | RTL responsive CSS؛ بررسی مرورگر production لازم است |
| فیلترها | آماده | وب و API: متن، نوع، دسته، شهر، تاریخ |
| مستندات و CI | آماده | README، docs، GitHub Actions tests workflow |
| health check | آماده | `GET /health` با بررسی دیتابیس و cache؛ در تست HTTP اعتبارسنجی شده |

## شفاف‌سازی محیطی

اجرای واقعی Kavenegar، ZarinPal live، HTTPS و مانیتورینگ منابع به کلیدها، دامنه، سرور و دسترسی شبکهٔ production نیاز دارد. این مقادیر در repository ذخیره نشده‌اند.
