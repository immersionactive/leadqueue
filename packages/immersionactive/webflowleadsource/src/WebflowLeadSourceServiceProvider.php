<?php

namespace ImmersionActive\WebflowLeadSource;

use App\LeadSourceTypeRegistry;
use Illuminate\Support\ServiceProvider;
use ImmersionActive\WebflowLeadSource\WebflowLeadSourceType;

class WebflowLeadSourceServiceProvider extends ServiceProvider
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
    public function boot(LeadSourceTypeRegistry $lead_source_type_registry)
    {

        // The Laravel package development docs say to call loadViewsFrom() in the boot() method:
        // https://laravel.com/docs/5.0/packages#views
        // ...but laravel/cashier (an official package) does it in the registerResources() method:
        // https://github.com/laravel/cashier/blob/10.0/src/CashierServiceProvider.php
        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'webflowleadsource');

        // $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');

        $lead_source_type_registry->register(WebflowLeadSourceType::class);

    }

}
