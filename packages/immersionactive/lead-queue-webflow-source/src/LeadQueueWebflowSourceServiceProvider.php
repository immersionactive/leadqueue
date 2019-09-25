<?php

namespace ImmersionActive\LeadQueueWebflowSource;

use App\SourceConfigTypeRegistry;
use Illuminate\Support\ServiceProvider;
use ImmersionActive\LeadQueueWebflowSource\WebflowSourceConfigType;

class LeadQueueWebflowSourceServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(SourceConfigTypeRegistry $source_config_type_registry)
    {

        // The Laravel package development docs say to call loadViewsFrom() in the boot() method:
        // https://laravel.com/docs/5.0/packages#views
        // ...but laravel/cashier (an official package) does it in the registerResources() method:
        // https://github.com/laravel/cashier/blob/10.0/src/CashierServiceProvider.php
        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'lead-queue-webflow-source');

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');

        $source_config_type_registry->register(WebflowSourceConfigType::class);

    }

}
