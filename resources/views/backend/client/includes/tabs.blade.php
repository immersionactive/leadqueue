<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link{{ $active_tab === 'overview' ? ' active' : '' }}" href="{{ route('admin.client.show', $client) }}"><i class="fas fa-user-circle"></i> Overview</a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ $active_tab === 'lead_sources' ? ' active' : '' }}" href="{{ route('admin.client.lead_source.index', $client) }}"><i class="fas fa-arrow-alt-circle-down"></i> Lead Sources</a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ $active_tab === 'lead_destinations' ? ' active' : '' }}" href="{{ route('admin.client.lead_destination.index', $client) }}"><i class="fas fa-arrow-alt-circle-up"></i> Lead Destinations</a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ $active_tab === 'mappings' ? ' active' : '' }}" href="{{ route('admin.client.mapping.index', $client) }}"><i class="fas fa-sitemap"></i> Mappings</a>
    </li>
</ul>
