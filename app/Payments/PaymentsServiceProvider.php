<?php

namespace Apithy\Payments\Conekta;

use Illuminate\Support\ServiceProvider;

class PaymentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Apithy\Payments\BillableRepositoryInterface', function () {
            return new EloquentBillableRepository();
        });
    }
}
