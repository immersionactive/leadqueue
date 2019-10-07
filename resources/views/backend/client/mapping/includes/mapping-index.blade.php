<div class="card">

    <div class="card-header">

        <div class="row">
            <div class="col-sm-8">
                Mappings
            </div>
            <div class="col-sm-4 pull-right">
                @can('client.mapping.edit')
                    <a href="{{ route('admin.client.mapping.edit', [$client]) }}" class="btn btn-info float-right" data-toggle="tooltip" title="@lang('labels.general.create_new')"><i class="fas fa-plus-circle"></i></a>
                @endcan
            </div>
        </div>

    </div> <!-- .card-header -->

    <div class="list-group list-group-flush">

        @if ($mappings->count() === 0)
            <div class="list-group-item">
                No mappings have been created for this client.
            </div>
        @else
            @foreach ($mappings as $mapping)
                <a href="{{ route('admin.client.mapping.show', [$client, $mapping]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center @if (isset($active_mapping_id) && $mapping->id === $active_mapping_id ) active @endif">
                    
                    {{ $mapping->name }}

                    @if ($mapping->is_active)
                        <span class="badge badge-success badge-pill">Active</span>
                    @else
                        <span class="badge badge-danger badge-pill">Inactive</span>
                    @endif

                </a>
            @endforeach
        @endif

    </div> <!-- .list-group -->

</div> <!-- .card -->

{{ $mappings->links() }}
