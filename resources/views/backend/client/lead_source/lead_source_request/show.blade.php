@extends('backend.layouts.app')

@section('title', $client->name . ': Lead Sources')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.lead_source.components.tabbox', ['client' => $client, 'lead_sources' => $lead_sources, 'source_config_type_classnames' => $source_config_type_classnames])

        <div class="table-responsive">
            <table class="table">
                <tbody>

                    <tr>
                        <th scope="row">ID</th>
                        <td>{{ $lead_source_request->id }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Date/Time Received</th>
                        <td>{{ timezone()->convertToLocal($lead_source_request->created_at) }} ({{ $lead_source_request->created_at->diffForHumans() }})</td>
                    </tr>

                    <tr>
                        <th scope="row">IP Address</th>
                        <td>{{ $lead_source_request->request_origin_ip }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Request Method</th>
                        <td>{{ $lead_source_request->request_method }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Request Headers<br><small>(cookies are excluded)</small></th>
                        <td>
                            @request_headers_json($lead_source_request->request_headers_json)
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Request Body (Raw)</th>
                        <td>{{ $lead_source_request->request_body_raw }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Request Body (Formatted)</th>
                        <td>
                            @if ($lead_source_request->request_content_type === 'application/json')
                                <pre>{!! json_encode(json_decode($lead_source_request->request_body_json), JSON_PRETTY_PRINT) !!}</pre>
                            @elseif ($lead_source_request->request_content_type === 'application/x-www-form-urlencoded')
                                {{-- TODO: actually format this --}}
                                @form_request_body_json($lead_source_request->request_body_json)
                            @else
                                No body parser available for this Content-Type.
                            @endif
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    @endcomponent

@endsection
