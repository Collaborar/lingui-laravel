<?php

namespace Lingui;

use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Lingui\Http\Middleware\ShareInertiaData;

class LinguiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('i18n', function ($app) {
            $locale = $app->getLocale();

            $i18n = new I18N(config('lingui.path', 'public/lang'), $locale);

            return $i18n;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $kernel = $this->app->make(Kernel::class);

        // $kernel->appendMiddlewareToGroup('web', LocaleSession::class);
        // $kernel->appendToMiddlewarePriority(LocaleSession::class);

        $kernel->appendMiddlewareToGroup('web', ShareInertiaData::class);
        $kernel->appendToMiddlewarePriority(ShareInertiaData::class);

        // $this->configureRoutes();
    }
}
