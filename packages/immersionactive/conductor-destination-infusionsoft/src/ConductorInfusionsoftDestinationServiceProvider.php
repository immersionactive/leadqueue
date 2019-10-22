<?php

namespace ImmersionActive\Conductor\Destinations\Infusionsoft;

use App\DestinationConfigTypeRegistry;
use Illuminate\Support\ServiceProvider;
use ImmersionActive\Conductor\Destinations\Infusionsoft\InfusionsoftDestinationConfigType;

class ConductorInfusionsoftDestinationServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }

    public function boot(DestinationConfigTypeRegistry $destination_config_type_registry)
    {

        // The Laravel package development docs say to call loadViewsFrom() in the boot() method:
        // https://laravel.com/docs/5.0/packages#views
        // ...but laravel/cashier (an official package) does it in the registerResources() method:
        // https://github.com/laravel/cashier/blob/10.0/src/CashierServiceProvider.php
        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'conductor-destination-infusionsoft');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $destination_config_type_registry->register(InfusionsoftDestinationConfigType::class);

    }

}
