<div class="card">

    <div class="card-header">

        <div class="row">
            <div class="col-sm-8">
                Lead Destinations
            </div>
            <div class="col-sm-4 pull-right">
                @can('client.lead_destination.edit')
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="TODO" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-plus-circle"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="TODO">
                            @foreach ($destination_config_type_classnames as $destination_config_type_classname)
                                <a class="dropdown-item" href="{{ route('admin.client.lead_destination.edit', ['client' => $client, 'type' => $destination_config_type_classname::getSlug()]) }}">{{ $destination_config_type_classname::getName() }}</a>
                            @endforeach
                        </div>
                    </div>
                @endcan
            </div>
        </div>

    </div> <!-- .card-header -->

    <div class="list-group list-group-flush">

        @if ($lead_destinations->count() === 0)
            <div class="list-group-item">
                This client has no lead destinations.
            </div>
        @else
            @foreach ($lead_destinations as $lead_destination)
                <a href="{{ route('admin.client.lead_destination.show', [$client, $lead_destination]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center @if (isset($active_lead_destination_id) && $lead_destination->id === $active_lead_destination_id ) active @endif">
                    {{ $lead_destination->name }}
                </a>
            @endforeach
        @endif

    </div> <!-- .list-group -->

</div> <!-- .card -->

{{ $lead_destinations->links() }}
