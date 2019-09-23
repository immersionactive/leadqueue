<?php

Breadcrumbs::for('admin.dashboard', function ($trail) {
    $trail->push(__('strings.backend.dashboard.title'), route('admin.dashboard'));
});

Breadcrumbs::for('admin.client.index', function ($trail) {
    $trail->push('Clients', route('admin.client.index'));
});

require __DIR__.'/auth.php';
require __DIR__.'/log-viewer.php';
