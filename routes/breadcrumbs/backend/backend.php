<?php

use App\Models\Client;
use App\Models\LeadSource;

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
    $trail->parent('admin.client.show', $client);
    $trail->push('Lead Sources', route('admin.client.lead_source.index', $client));
    $trail->push($lead_source->name, route('admin.client.lead_source.create', $client));
});

Breadcrumbs::for('admin.client.lead_source.create', function ($trail, Client $client) {
    $trail->parent('admin.client.show', $client);
    $trail->push('Lead Sources', route('admin.client.lead_source.index', $client));
    $trail->push('Create Lead Source', route('admin.client.lead_source.create', $client));
});

require __DIR__.'/auth.php';
require __DIR__.'/log-viewer.php';
