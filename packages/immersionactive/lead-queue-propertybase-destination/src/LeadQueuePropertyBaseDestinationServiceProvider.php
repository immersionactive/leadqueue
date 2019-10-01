<?php

namespace ImmersionActive\LeadQueuePropertyBaseDestination;

use App\DestinationConfigTypeRegistry;
use Illuminate\Support\ServiceProvider;
use ImmersionActive\LeadQueuePropertyBaseDestination\PropertyBaseDestinationConfigType;

class LeadQueuePropertyBaseDestinationServiceProvider extends ServiceProvider
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
    public function boot(DestinationConfigTypeRegistry $destination_config_type_registry)
    {

        // The Laravel package development docs say to call loadViewsFrom() in the boot() method:
        // https://laravel.com/docs/5.0/packages#views
        // ...but laravel/cashier (an official package) does it in the registerResources() method:
        // https://github.com/laravel/cashier/blob/10.0/src/CashierServiceProvider.php
        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'lead-queue-propertybase-destination');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');

        $destination_config_type_registry->register(PropertyBaseDestinationConfigType::class);

    }

}
