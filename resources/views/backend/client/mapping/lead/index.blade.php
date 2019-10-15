@extends('backend.layouts.app')

@section('title', $mapping->name . ': Fields')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.mapping.components.tabbox', ['client' => $client, 'mappings' => $mappings, 'active_mapping_id' => $mapping->id])

        {{-- Filters --}}

        {{ html()->form('GET', route('admin.client.mapping.lead.index', [$client, $mapping]))->class('form-inline mb-4')->novalidate()->open() }}

            <div class="form-group mr-4">
                {{ html()->label('Status')->for('status')->class('mr-2') }}
                {{ html()->select('status', $lead_statuses_list, $filters['status'])->class('form-control' . ($errors->has('status') ? ' is-invalid' : '')) }}
            </div>

            <div class="form-group mr-4">
                {{ html()->label('Received From')->for('created_at_start')->class('mr-2') }}
                {{ html()->text('created_at_start', $filters['created_at_start'])->placeholder('yyyy-mm-dd hh:mm:ss')->class('form-control' . ($errors->has('created_at_start') ? ' is-invalid' : '')) }}
            </div>

            <div class="form-group mr-4">
                {{ html()->label('Received To')->for('created_at_end')->class('mr-2') }}
                {{ html()->text('created_at_end', $filters['created_at_end'])->placeholder('yyyy-mm-dd hh:mm:ss')->class('form-control' . ($errors->has('created_at_end') ? ' is-invalid' : '')) }}
            </div>

            {{ html()->submit('Search')->class('btn btn-primary mr-2') }}

            <a href="{{ route('admin.client.mapping.lead.index', [$client, $mapping]) }}" class="btn btn-secondary">
                Clear Filters
            </a>

            <a href="{{ route('admin.client.mapping.lead.download', ['client' => $client, 'mapping' => $mapping] + $filters) }}" class="btn btn-info" data-toggle="tooltip" title="Download Leads as CSV">
                <i class="fas fa-download"></i>
            </a>

        {{ html()->form()->close() }}

        {{-- Search Results --}}

        @if ($leads->count() === 0)

            <p>No matching leads were found.</p>

        @else

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Received</th>
                            <th scope="col">Status</th>
                            <th scope="col">
                                Append Failures
                            </th>
                            <th scope="col">
                                Destination Failures
                            </th>
                            @can('client.lead_source.lead_source_request.show')
                                <th scope="col">Lead Source Request</th>
                            @endcan
                            <th scope="col">@lang('labels.general.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leads as $lead)
                            <tr>
                                <td>{{ $lead->id }}</td>
                                <td>
                                    {{ timezone()->convertToLocal($lead->created_at) }}<br>
                                    <small>({{ $lead->created_at->diffForHumans() }})</small>
                                </td>
                                <td>@include('backend.includes.partials.lead-status-badge', ['status' => $lead->status])</td>
                                <td>{{ $lead->failed_append_attempts }}</td>
                                <td>{{ $lead->failed_destination_attempts }}</td>
                                @can('client.lead_source.lead_source_request.show')
                                    <td>
                                        <a href="{{ route('admin.client.lead_source.lead_source_request.show', [$client, $mapping->lead_source_id, $lead->lead_source_request_id]) }}">
                                            {{ $lead->lead_source_request_id }}
                                        </a>
                                    </td>
                                @endcan
                                <td>

                                    <div class="btn-group" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">

                                        @can('client.mapping.lead.show')
                                            <a href="{{ route('admin.client.mapping.lead.show', [$client, $mapping, $lead]) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.view')" class="btn btn-info">
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

            {{-- TODO: include filter parameters in the links, by using appends() --}}
            {{ $leads->appends($filters)->links() }}

        @endif

    @endcomponent
    
@endsection
