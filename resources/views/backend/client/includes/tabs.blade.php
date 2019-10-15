<ul class="nav nav-tabs">
    @can('client.show')
        <li class="nav-item">
            <a class="nav-link{{ $active_tab === 'overview' ? ' active' : '' }}" href="{{ route('admin.client.show', $client) }}"><i class="fas fa-user-circle"></i> Overview</a>
        </li>
    @endcan
    @can('client.mappings.index')
        <li class="nav-item">
            <a class="nav-link{{ $active_tab === 'mappings' ? ' active' : '' }}" href="{{ route('admin.client.mapping.index', $client) }}"><i class="fas fa-sitemap"></i> Mappings</a>
        </li>
    @endcan
    @can('client.lead_sources.index')
        <li class="nav-item">
            <a class="nav-link{{ $active_tab === 'lead_sources' ? ' active' : '' }}" href="{{ route('admin.client.lead_source.index', $client) }}"><i class="fas fa-arrow-alt-circle-down"></i> Lead Sources</a>
        </li>
    @endcan
    @can('client.lead_destinations.index')
        <li class="nav-item">
            <a class="nav-link{{ $active_tab === 'lead_destinations' ? ' active' : '' }}" href="{{ route('admin.client.lead_destination.index', $client) }}"><i class="fas fa-arrow-alt-circle-up"></i> Lead Destinations</a>
        </li>
    @endcan
</ul>
