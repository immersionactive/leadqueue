@extends('backend.layouts.app')

@section('title', $lead_source->name . ' < Lead Sources < ' . $client->name)

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.lead_source.components.tabbox', ['client' => $client, 'lead_sources' => $lead_sources, 'active_lead_source_id' => $lead_source->id, 'source_config_type_classnames' => $source_config_type_classnames])

        <div class="row mb-4">

            <div class="col-md-6">
                <h4>{{ $lead_source->name }}</h4>
            </div>

            @canany(['client.lead_source.update', 'client.lead_source.destroy'])
                <div class="col-md-6 pull-right">

                    <div class="btn-group float-right" role="group" aria-label="TODO">
                       
                        @can('client.lead_source.update', $client)
                            <a href="{{ route('admin.client.lead_source.edit', [$client, $lead_source]) }}" class="btn btn-success" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                        @endcan

                        @can('client.lead_source.destroy', $client)
                            <a href="{{ route('admin.client.lead_source.destroy', [$client, $lead_source]) }}"
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
                        <td>{{ $lead_source->name }}</td>
                    </tr>

                    {{-- Active --}}

                    <tr>
                        <th>Active</th>
                        <td>
                            @include('backend.includes.partials.yn-badge', ['active' => $lead_source->is_active])
                        </td>
                    </tr>

                    {{-- Notes --}}

                    <tr>
                        <th>Notes</th>
                        <td>{!! nl2br(e($lead_source->notes)) !!}</td>
                    </tr>

                    @include($source_config_type_classname::getShowView())

                </tbody>
            </table>
        </div>

    @endcomponent
    
@endsection
