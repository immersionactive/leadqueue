@extends('backend.layouts.app')

@section('title', $lead_destination->name . ' < Lead Destinations < ' . $client->name)

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.lead_destination.components.tabbox', ['client' => $client, 'lead_destinations' => $lead_destinations, 'active_lead_destination_id' => $lead_destination->id, 'destination_config_type_classnames' => $destination_config_type_classnames])

        <div class="row mb-4">

            <div class="col-md-6">
                <h4>{{ $lead_destination->name }}: Destination Appends</h4>
            </div>

            @canany(['client.lead_destination.update', 'client.lead_destination.destroy'])
                <div class="col-md-6 pull-right">

                    <div class="btn-group float-right" role="group" aria-label="TODO">
                       
                        @can('client.lead_destination.destination_append.edit')
                            <a href="{{ route('admin.client.lead_destination.destination_append.edit', [$client, $lead_destination]) }}" class="btn btn-info" data-toggle="tooltip" title="@lang('labels.general.create_new')"><i class="fas fa-plus-circle"></i></a>
                        @endcan

                    </div>

                </div>
            @endcanany

        </div>

        @if ($destination_appends->count() === 0)

            <p>There are no appends configured for this lead destination.</p>

        @else

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Append Output</th>
                            <th scope="col">Destination Field</th>
                            <th scope="col">@lang('labels.general.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($destination_appends as $destination_append)
                            <tr>
                                <td>
                                    @if (array_key_exists($destination_append->append_output_slug, $append_outputs_list))
                                        {{ $append_outputs_list[$destination_append->append_output_slug] }}
                                    @else
                                        Unknown
                                    @endif
                                </td>
                                <td>
                                    @include($destination_config_type_classname::getDestinationAppendConfigSummaryView(), ['destination_append_config' => $destination_append->destination_append_config])
                                </td>
                                <td>

                                    <div class="btn-group" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">

                                        @can('client.lead_destination.destination_append.edit')
                                            <a href="{{ route('admin.client.lead_destination.destination_append.edit', [$client, $lead_destination, $destination_append]) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.edit')" class="btn btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('client.lead_destination.destination_append.destroy')
                                            <a href="{{ route('admin.client.lead_destination.destination_append.destroy', [$client, $lead_destination, $destination_append]) }}"
                                               data-method="post"
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

            {{ $destination_appends->links() }}

        @endif

    @endcomponent

@endsection
