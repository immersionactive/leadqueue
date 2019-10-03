<?php

namespace App\Providers;

use App\SourceConfigTypeRegistry;
use App\DestinationConfigTypeRegistry;
use Illuminate\Support\Facades\View;
use App\Http\Composers\GlobalComposer;
use Illuminate\Support\ServiceProvider;
use App\Http\Composers\Backend\SidebarComposer;
use App\Models\LeadSource;
use App\Models\LeadDestination;
use Illuminate\Routing\Route;

/**
 * Class ComposerServiceProvider.
 */
class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function boot(SourceConfigTypeRegistry $source_config_type_registry, DestinationConfigTypeRegistry $destination_config_type_registry)
    {
        // Global
        View::composer(
            // This class binds the $logged_in_user variable to every view
            '*',
            GlobalComposer::class
        );

        // Frontend

        // Backend
        View::composer(
            // This binds items like number of users pending approval when account approval is set to true
            'backend.includes.sidebar',
            SidebarComposer::class
        );

        // For lead-source-index.blade.php
        View::composer(
            [
                'backend.client.lead_source.index',
                'backend.client.lead_source.show',
                'backend.client.lead_source.create-edit'
            ],
            function ($view) use ($source_config_type_registry) {
                $client_id = $view->getData()['client']->id;
                $lead_sources = LeadSource::where('client_id', $client_id)->orderBy('name')->paginate(20, ['*'], 'leadSourcePage');
                $view->with([
                    'lead_sources' => $lead_sources,
                    'source_config_type_classnames' => $source_config_type_registry->getRegisteredTypes()
                ]);
            }
        );

        // For lead-destination-index.blade.php
        View::composer(
            [
                'backend.client.lead_destination.index',
                'backend.client.lead_destination.show',
                'backend.client.lead_destination.create-edit'
            ],
            function ($view) use ($destination_config_type_registry) {
                $client_id = $view->getData()['client']->id;
                $lead_destinations = LeadDestination::where('client_id', $client_id)->orderBy('name')->paginate(20, ['*'], 'leadDestinationPage');
                $view->with([
                    'lead_destinations' => $lead_destinations,
                    'destination_config_type_classnames' => $destination_config_type_registry->getRegisteredTypes()
                ]);
            }
        );

    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        //
    }
}
