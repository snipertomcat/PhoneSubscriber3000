<?php

namespace Apithy\Providers;

use Apithy\Http\Composers\ApplicationComposer;
use Illuminate\Support\ServiceProvider;

/**
 * Class ComposerServiceProvider
 * @package Apithy\Providers
 */
class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', ApplicationComposer::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
