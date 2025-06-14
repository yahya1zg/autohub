<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // Bu satır, routes/api.php dosyasını yükler ve
            // içindeki tüm rotaların başına /api ekler.
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Bu satır, web arayüzü rotalarını yükler.
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
// Bu sınıf, uygulamanın rotalarını tanımlar ve yapılandırır.
// Ayrıca, API istekleri için hız sınırlama kurallarını da belirler.