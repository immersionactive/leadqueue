@extends('backend.layouts.app')

@section('title', $client->name . ': Lead Sources')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.lead_source.components.tabbox', ['client' => $client, 'lead_sources' => $lead_sources, 'source_config_type_classnames' => $source_config_type_classnames])

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Received</th>
                        <th scope="col">HTTP Method</th>
                        <th scope="col">IP Address</th>
                        <th scope="col">@lang('labels.general.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lead_source_requests as $lead_source_request)
                        <tr>
                            <td>{{ $lead_source_request->id }}</td>
                            <td>
                                {{ timezone()->convertToLocal($lead_source_request->created_at) }}<br>
                                <small>({{ $lead_source_request->created_at->diffForHumans() }})</small>
                            </td>
                            <td>{{ $lead_source_request->request_method }}</td>
                            <td>{{ $lead_source_request->request_origin_ip }}</td>
                            <td>

                                <div class="btn-group" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">

                                    @can('client.lead_source.lead_source_request.show')
                                        <a href="{{ route('admin.client.lead_source.lead_source_request.show', [$client, $lead_source, $lead_source_request]) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.view')" class="btn btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan

                                </div>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $lead_source_requests->links() }}

    @endcomponent

@endsection
