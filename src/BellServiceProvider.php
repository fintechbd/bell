<?php

namespace Fintech\Bell;

use Fintech\Bell\Channels\PushChannel;
use Fintech\Bell\Commands\InstallCommand;
use Fintech\Bell\Commands\ScheduledNotificationCommand;
use Fintech\Bell\Providers\EventServiceProvider;
use Fintech\Bell\Providers\RepositoryServiceProvider;
use Fintech\Core\Traits\Packages\RegisterPackageTrait;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class BellServiceProvider extends ServiceProvider
{
    use RegisterPackageTrait;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->packageCode = 'bell';

        $this->mergeConfigFrom(
            __DIR__.'/../config/bell.php', 'fintech.bell'
        );

        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->injectOnConfig();

        $this->publishes([
            __DIR__.'/../config/bell.php' => config_path('fintech/bell.php'),
        ], 'fintech-bell-config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'bell');

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/bell'),
        ], 'fintech-bell-lang');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bell');

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/bell'),
        ], 'fintech-bell-views');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                ScheduledNotificationCommand::class
            ]);
        }

        $this->extendNotificationChannels();
    }

    private function extendNotificationChannels(): void
    {
        Notification::extend('push', function ($app) {
            return $app->make(PushChannel::class);
        });
    }
}
