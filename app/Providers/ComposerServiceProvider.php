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
use App\Models\Mapping;
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
                'backend.client.lead_source.edit',
                'backend.client.lead_source.lead_source_request.index',
                'backend.client.lead_source.lead_source_request.show'
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
                'backend.client.lead_destination.edit',
                'backend.client.lead_destination.destination_append.index',
                'backend.client.lead_destination.destination_append.show',
                'backend.client.lead_destination.destination_append.edit',
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

        View::composer(
            [
                'backend.client.mapping.index',
                'backend.client.mapping.show',
                'backend.client.mapping.edit',
                'backend.client.mapping.mapping_field.index',
                'backend.client.mapping.mapping_field.show',
                'backend.client.mapping.mapping_field.edit',
                'backend.client.mapping.lead.index',
                'backend.client.mapping.lead.show',
            ],
            function ($view) {
                $client_id = $view->getData()['client']->id;
                $mappings = Mapping::where('client_id', $client_id)->orderBy('name')->paginate(20, ['*'], 'mappingPage');
                $view->with([
                    'mappings' => $mappings
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
