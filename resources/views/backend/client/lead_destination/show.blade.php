@extends('backend.layouts.app')

@section('title', $lead_destination->name . ' < Lead Destinations < ' . $client->name)

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.lead_destination.components.tabbox', ['client' => $client, 'lead_destinations' => $lead_destinations, 'active_lead_destination_id' => $lead_destination->id, 'destination_config_type_classnames' => $destination_config_type_classnames])

        <div class="row mb-4">

            <div class="col-md-6">
                <h4>{{ $lead_destination->name }}</h4>
            </div>

            @canany(['client.lead_destination.update', 'client.lead_destination.destroy'])
                <div class="col-md-6 pull-right">

                    <div class="btn-group float-right" role="group" aria-label="TODO">
                       
                        @can('client.lead_destination.update', $client)
                            <a href="{{ route('admin.client.lead_destination.edit', [$client, $lead_destination]) }}" class="btn btn-success" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                        @endcan

                        @can('client.lead_destination.destroy', $client)
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

                </div>
            @endcanany

        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>

                    {{-- Name --}}

                    <tr>
                        <th>Name</th>
                        <td>{{ $lead_destination->name }}</td>
                    </tr>

                    {{-- Active --}}

                    <tr>
                        <th>Active</th>
                        <td>
                            @include('backend.includes.partials.yn-badge', ['active' => $lead_destination->is_active])
                        </td>
                    </tr>

                    {{-- Notes --}}

                    <tr>
                        <th>Notes</th>
                        <td>{!! nl2br(e($lead_destination->notes)) !!}</td>
                    </tr>

                    @include($destination_config_type_classname::getShowView())

                </tbody>
            </table>
        </div>

    @endcomponent
    
@endsection
