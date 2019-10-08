<?php

use App\Models\Client;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadDestination;
use App\Models\Mapping;
use App\Models\MappingField;

Breadcrumbs::for('admin.dashboard', function ($trail) {
    $trail->push(__('strings.backend.dashboard.title'), route('admin.dashboard'));
});

/**
 * Clients
 */

Breadcrumbs::for('admin.client.index', function ($trail) {
    $trail->push('Clients', route('admin.client.index'));
});

Breadcrumbs::for('admin.client.show', function ($trail, Client $client) {
    $trail->parent('admin.client.index');
    $trail->push($client->name, route('admin.client.show', $client));
});

Breadcrumbs::for('admin.client.edit', function ($trail, Client $client = null) {
    if ($client) {
        $trail->parent('admin.client.show', $client);
        $trail->push('Edit', route('admin.client.edit', $client));
    } else {
        $trail->parent('admin.client.index');
        $trail->push('New Client', route('admin.client.edit', $client));
    }
});

/**
 * Lead Sources
 */

Breadcrumbs::for('admin.client.lead_source.index', function ($trail, Client $client) {
    $trail->parent('admin.client.show', $client);
    $trail->push('Lead Sources', route('admin.client.lead_source.index', $client));
});

Breadcrumbs::for('admin.client.lead_source.show', function ($trail, Client $client, LeadSource $lead_source) {
    $trail->parent('admin.client.lead_source.index', $client);
    $trail->push($lead_source->name, route('admin.client.lead_source.show', [$client, $lead_source]));
});

Breadcrumbs::for('admin.client.lead_source.edit', function ($trail, Client $client, LeadSource $lead_source = null) {
    if ($lead_source) {
        $trail->parent('admin.client.lead_source.show', $client, $lead_source);
        $trail->push('Edit', route('admin.client.lead_source.edit', [$client, $lead_source]));
    } else {
        $trail->parent('admin.client.lead_source.index', $client);
        $trail->push('New Lead Source', route('admin.client.lead_source.edit', [$client]));
    }
});

/**
 * Leads
 */

Breadcrumbs::for('admin.client.lead_source.lead.index', function ($trail, Client $client, LeadSource $lead_source) {
    $trail->parent('admin.client.lead_source.show', $client, $lead_source);
    $trail->push('Leads', route('admin.client.lead_source.lead.index', [$client, $lead_source]));
});

Breadcrumbs::for('admin.client.lead_source.lead.show', function ($trail, Client $client, LeadSource $lead_source, Lead $lead) {
    $trail->parent('admin.client.lead_source.lead.index', $client, $lead_source);
    $trail->push('View Lead ' . $lead->id, route('admin.client.lead_source.lead.show', [$client, $lead_source, $lead]));
});

/**
 * Lead Destinations
 */

Breadcrumbs::for('admin.client.lead_destination.index', function ($trail, Client $client) {
    $trail->parent('admin.client.show', $client);
    $trail->push('Lead Destinations', route('admin.client.lead_destination.index', $client));
});

Breadcrumbs::for('admin.client.lead_destination.show', function ($trail, Client $client, LeadDestination $lead_destination) {
    $trail->parent('admin.client.lead_destination.index', $client);
    $trail->push($lead_destination->name, route('admin.client.lead_destination.edit', [$client, $lead_destination]));
});

Breadcrumbs::for('admin.client.lead_destination.edit', function ($trail, Client $client, LeadDestination $lead_destination = null) {
    if ($lead_destination) {
        $trail->parent('admin.client.lead_destination.show', $client, $lead_destination);
        $trail->push('Edit', route('admin.client.lead_destination.edit', [$client, $lead_destination]));
    } else {
        $trail->parent('admin.client.lead_destination.index', $client);
        $trail->push('New Lead Destination', route('admin.client.lead_destination.edit', [$client]));
    }
});

/**
 * Destination Appends
 */

Breadcrumbs::for('admin.client.lead_destination.destination_append.index', function ($trail, Client $client, LeadDestination $lead_destination) {
    $trail->parent('admin.client.lead_destination.show', $client, $lead_destination);
    $trail->push('View Destination Appends', route('admin.client.lead_destination.destination_append.index', [$client, $lead_destination]));
});

Breadcrumbs::for('admin.client.lead_destination.destination_append.edit', function ($trail, Client $client, LeadDestination $lead_destination) {
    $trail->parent('admin.client.lead_destination.destination_append.index', $client, $lead_destination);
    $trail->push('Create Destination Append', route('admin.client.lead_destination.destination_append.edit', [$client, $lead_destination]));
});

/**
 * Mappings
 */

Breadcrumbs::for('admin.client.mapping.index', function ($trail, Client $client) {
    $trail->parent('admin.client.show', $client);
    $trail->push('Mappings', route('admin.client.mapping.index', $client));
});

Breadcrumbs::for('admin.client.mapping.show', function ($trail, Client $client, Mapping $mapping) {
    $trail->parent('admin.client.mapping.index', $client);
    $trail->push($mapping->name, route('admin.client.mapping.show', [$client, $mapping]));
});

Breadcrumbs::for('admin.client.mapping.edit', function ($trail, Client $client, Mapping $mapping = null) {
    if ($mapping) {
        $trail->parent('admin.client.mapping.show', $client, $mapping);
        $trail->push('Edit', route('admin.client.mapping.edit', [$client, $mapping]));
    } else {
        $trail->parent('admin.client.mapping.index', $client);
        $trail->push('Create Mapping', route('admin.client.mapping.edit', [$client, null]));
    }
});

/**
 * MappingFields
 */

Breadcrumbs::for('admin.client.mapping.mapping_field.index', function ($trail, Client $client, Mapping $mapping) {
    $trail->parent('admin.client.mapping.show', $client, $mapping);
    $trail->push('Fields', route('admin.client.mapping.mapping_field.index', [$client, $mapping]));
});

Breadcrumbs::for('admin.client.mapping.mapping_field.show', function ($trail, Client $client, Mapping $mapping, MappingField $mapping_field) {
    $trail->parent('admin.client.mapping.mapping_field.index', $client, $mapping);
    $trail->push($mapping_field->id, route('admin.client.mapping.mapping_field.show', [$client, $mapping, $mapping_field]));
});

Breadcrumbs::for('admin.client.mapping.mapping_field.edit', function ($trail, Client $client, Mapping $mapping, MappingField $mapping_field = null) {
    if ($mapping_field) {
        $trail->parent('admin.client.mapping.mapping_field.show', $client, $mapping, $mapping_field);
        $trail->push('Edit', route('admin.client.mapping.mapping_field.edit', [$client, $mapping, $mapping_field]));
    } else {
        $trail->parent('admin.client.mapping.mapping_field.index', $client, $mapping);
        $trail->push('Create Field', route('admin.client.mapping.mapping_field.edit', [$client, $mapping, null]));
    }
});

require __DIR__.'/auth.php';
require __DIR__.'/log-viewer.php';
