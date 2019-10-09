@extends('backend.layouts.app')

@section('title', $mapping->name . ': Fields')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.mapping.components.tabbox', ['client' => $client, 'mappings' => $mappings, 'active_mapping_id' => $mapping->id])

        @if ($leads->count() === 0)

            <p>No leads have been received by this mapping.</p>

        @else

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Received</th>
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

            {{ $leads->links() }}

        @endif

    @endcomponent
    
@endsection
