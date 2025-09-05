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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootInertia();
        $this->registerConsoleCommands();
    }

    /**
     * Boot any Inertia related services.
     *
     * @return void
     */
    protected function bootInertia(): void
    {
        $kernel = $this->app->make(Kernel::class);

        $kernel->appendMiddlewareToGroup('web', ShareInertiaData::class);
        $kernel->appendToMiddlewarePriority(ShareInertiaData::class);
    }

    protected function registerConsoleCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Commands\MakeJson::class,
        ]);
    }
}
