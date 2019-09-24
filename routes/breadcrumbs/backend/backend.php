<?php

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

Breadcrumbs::for('admin.client.show', function ($trail, $id) {
    $trail->parent('admin.client.index');
    $trail->push('View Client', route('admin.client.show', $id));
});

Breadcrumbs::for('admin.client.edit', function ($trail, $id) {
    $trail->parent('admin.client.index');
    $trail->push('Edit Client', route('admin.client.edit', $id));
});

require __DIR__.'/auth.php';
require __DIR__.'/log-viewer.php';
