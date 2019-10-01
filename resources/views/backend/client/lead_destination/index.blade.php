@extends('backend.layouts.app')

@section('title', $client->name . ': Lead Destinations')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    <div class="card">

        <div class="card-body">

            <div class="row">

                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        {{ $client->name }}
                    </h4>
                </div>

                <div class="col-sm-7 pull-right">
                    
                    <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                        @can('admin.client.lead_destination.create')
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="TODO" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-plus-circle"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="TODO">
                                    @foreach ($destination_config_type_classnames as $destination_config_type_classname)
                                        <a class="dropdown-item" href="{{ route('admin.client.lead_destination.create', [$client, $destination_config_type_classname::getSlug()]) }}">{{ $destination_config_type_classname::getName() }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endcan
                    </div>

                </div>

            </div>

            <div class="row mt-4">
                <div class="col">

                    @include('backend.client.includes.tabs', ['active_tab' => 'lead_destinations', 'client' => $client])

                    <div class="tab-content">
                        <div class="tab-pane active" role="tabpanel" aria-expanded="true">

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Lead Destination</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">URL</th>
                                            <th scope="col">Active</th>
                                            <th scope="col">@lang('labels.general.actions')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lead_destinations as $lead_destination)
                                            <tr>
                                                <td>
                                                    @if ($lead_destination->trashed())
                                                        <strike>{{ $lead_destination->name }}</strike>
                                                    @else
                                                        {{ $lead_destination->name }}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{--
                                                    @if (array_key_exists($lead_destination->destination_config_type, $destination_config_types_by_model_classname)) 
                                                        {{ $destination_config_types_by_model_classname[$lead_destination->destination_config_type] }}
                                                    @else
                                                        <span class="badge badge-danger">Unknown: {{ $lead_destination->destination_config_type }}</span>
                                                    @endif
                                                    --}}
                                                </td>
                                                <td>
                                                    @if ($lead_destination->destination_config)
                                                        {{ route('api.insert', $lead_destination) }}
                                                    @else
                                                        <span class="badge badge-danger">Config Not Found</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @include('backend.includes.partials.yn-badge', ['active' => $lead_destination->is_active])
                                                </td>
                                                <td>

                                                    <div class="btn-group" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">

                                                        @can('client.lead_destination.show')
                                                            <a href="{{ route('admin.client.lead_destination.show', [$client, $lead_destination]) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.view')" class="btn btn-info">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endcan

                                                        @can('client.lead_destination.update')
                                                            <a href="{{ route('admin.client.lead_destination.edit', [$client, $lead_destination]) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.edit')" class="btn btn-primary">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endcan

                                                        @can('client.lead_destination.delete')
                                                            <a href="{{ route('admin.client.lead_destination.destroy', [$client, $lead_destination]) }}"
                                                               data-method="delete"
                                                               data-trans-button-cancel="@lang('buttons.general.cancel')"
                                                               data-trans-button-confirm="@lang('buttons.general.crud.delete')"
                                                               data-trans-title="@lang('strings.backend.general.are_you_sure')"
                                                               class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.delete')">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        @endcan

                                                    </div>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $lead_destinations->links() }}

                        </div>
                    </div> <!-- .tab-content -->

                </div>
            </div>

        </div> <!-- .card-body -->

    </div> <!-- .card -->
    
@endsection
