<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by((string) ($request->user()?->getAuthIdentifier() ?: $request->ip())));
        Blade::directive('jalali', fn ($expression) => "<?php echo ($expression) ? jdate($expression)->format('Y/m/d H:i') : '—'; ?>");
    }
}
