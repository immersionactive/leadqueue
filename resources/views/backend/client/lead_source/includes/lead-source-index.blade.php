<div class="card">

    <div class="card-header">

        <div class="row">
            <div class="col-sm-8">
                Lead Sources
            </div>
            <div class="col-sm-4 pull-right">
                @can('client.lead_source.edit')
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="TODO" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-plus-circle"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="TODO">
                            @foreach ($source_config_type_classnames as $source_config_type_classname)
                                <a class="dropdown-item" href="{{ route('admin.client.lead_source.edit', ['client' => $client, 'lead_source' => null, 'type' => $source_config_type_classname::getSlug()]) }}">{{ $source_config_type_classname::getName() }}</a>
                            @endforeach
                        </div>
                    </div>
                @endcan
            </div>
        </div>

    </div> <!-- .card-header -->

    <div class="list-group list-group-flush">

        @if ($lead_sources->count() === 0)
            <div class="list-group-item">
                There are no lead sources for this client.
            </div>
        @else
            @foreach ($lead_sources as $lead_source)
                <a href="{{ route('admin.client.lead_source.show', [$client, $lead_source]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center @if (isset($active_lead_source_id) && $lead_source->id === $active_lead_source_id ) active @endif">
                    {{ $lead_source->name }}
                </a>
            @endforeach
        @endif

    </div> <!-- .list-group -->

</div> <!-- .card -->

{{ $lead_sources->links() }}
