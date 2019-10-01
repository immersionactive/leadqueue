<?php

use App\Models\Client;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadDestination;

Breadcrumbs::for('admin.dashboard', function ($trail) {
    $trail->push(__('strings.backend.dashboard.title'), route('admin.dashboard'));
});

Breadcrumbs::for('admin.client.index', function ($trail) {
    $trail->push('Clients', route('admin.client.index'));
});

Breadcrumbs::for('admin.client.create', function ($trail) {
    $trail->parent('admin.client.index');
    $trail->push('Create Client', route('admin.client.create'));
});

Breadcrumbs::for('admin.client.show', function ($trail, Client $client) {
    $trail->parent('admin.client.index');
    $trail->push($client->name, route('admin.client.show', $client));
});

Breadcrumbs::for('admin.client.edit', function ($trail, Client $client) {
    $trail->parent('admin.client.index');
    $trail->push($client->name, route('admin.client.show', $client));
    $trail->push('Edit Client', route('admin.client.edit', $client));
});

Breadcrumbs::for('admin.client.lead_source.index', function ($trail, Client $client) {
    $trail->parent('admin.client.show', $client);
    $trail->push('Lead Sources', route('admin.client.lead_source.index', $client));
});

Breadcrumbs::for('admin.client.lead_source.show', function ($trail, Client $client, LeadSource $lead_source) {
    $trail->parent('admin.client.lead_source.index', $client);
    $trail->push($lead_source->name, route('admin.client.lead_source.create', [$client, $lead_source]));
});

Breadcrumbs::for('admin.client.lead_source.create', function ($trail, Client $client, $lead_source_type_slug) {
    $trail->parent('admin.client.lead_source.index', $client);
    $trail->push('Create Lead Source', route('admin.client.lead_source.create', [$client, $lead_source_type_slug]));
});

Breadcrumbs::for('admin.client.lead_source.edit', function ($trail, Client $client, LeadSource $lead_source) {
    $trail->parent('admin.client.lead_source.show', $client, $lead_source);
    $trail->push('Edit Lead Source', route('admin.client.lead_source.edit', [$client, $lead_source]));
});

Breadcrumbs::for('admin.client.lead_source.lead.index', function ($trail, Client $client, LeadSource $lead_source) {
    $trail->parent('admin.client.lead_source.show', $client, $lead_source);
    $trail->push('Leads', route('admin.client.lead_source.lead.index', [$client, $lead_source]));
});

Breadcrumbs::for('admin.client.lead_source.lead.show', function ($trail, Client $client, LeadSource $lead_source, Lead $lead) {
    $trail->parent('admin.client.lead_source.lead.index', $client, $lead_source);
    $trail->push('View Lead ' . $lead->id, route('admin.client.lead_source.lead.show', [$client, $lead_source, $lead]));
});

Breadcrumbs::for('admin.client.lead_destination.index', function ($trail, Client $client) {
    $trail->parent('admin.client.show', $client);
    $trail->push('Lead Destinations', route('admin.client.lead_destination.index', $client));
});

Breadcrumbs::for('admin.client.lead_destination.show', function ($trail, Client $client, LeadDestination $lead_destination) {
    $trail->parent('admin.client.lead_destination.index', $client);
    $trail->push($lead_destination->name, route('admin.client.lead_destination.create', [$client, $lead_destination]));
});

Breadcrumbs::for('admin.client.lead_destination.create', function ($trail, Client $client, $lead_destination_type_slug) {
    $trail->parent('admin.client.lead_destination.index', $client);
    $trail->push('Create Lead Destination', route('admin.client.lead_destination.create', [$client, $lead_destination_type_slug]));
});

Breadcrumbs::for('admin.client.lead_destination.edit', function ($trail, Client $client, LeadDestination $lead_destination) {
    $trail->parent('admin.client.lead_destination.show', $client, $lead_destination);
    $trail->push('Edit Lead Source', route('admin.client.lead_destination.edit', [$client, $lead_destination]));
});

require __DIR__.'/auth.php';
require __DIR__.'/log-viewer.php';
