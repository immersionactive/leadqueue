@extends('backend.layouts.app')

@section('title', $mapping->name . ' < Mappings < ' . $client->name)

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.mapping.components.tabbox', ['client' => $client, 'mappings' => $mappings, 'active_mapping_id' => $mapping->id])

        <div class="row mb-4">

            <div class="col-md-6">
                <h4>{{ $mapping->name }}</h4>
            </div>

            @canany(['client.mapping.update', 'client.mapping.destroy'])
                <div class="col-md-6 pull-right">

                    <div class="btn-group float-right" role="group" aria-label="TODO">

                        @can('client.mapping.mapping_field.index', $client)
                            <a href="{{ route('admin.client.mapping.mapping_field.index', [$client, $mapping]) }}" class="btn btn-info" data-toggle="tooltip" title="View Fields"><i class="fas fa-list"></i></a>
                        @endcan
                       
                        @can('client.mapping.update', $client)
                            <a href="{{ route('admin.client.mapping.edit', [$client, $mapping]) }}" class="btn btn-success" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                        @endcan

                        @can('client.mapping.destroy', $client)
                            <a href="{{ route('admin.client.mapping.destroy', [$client, $mapping]) }}"
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
                        <th scope="row">Name</th>
                        <td>{{ $mapping->name }}</td>
                    </tr>

                    {{-- Active --}}

                    <tr>
                        <th scope="row">Active</th>
                        <td>
                            @include('backend.includes.partials.yn-badge', ['active' => $mapping->is_active])
                        </td>
                    </tr>

                    {{-- Notes --}}

                    <tr>
                        <th scope="row">Notes</th>
                        <td>{!! nl2br(e($mapping->notes)) !!}</td>
                    </tr>

                    {{-- Lead Source --}}

                    <tr>
                        <th scope="row">Lead Source</th>
                        <td>
                            @can('client.lead_source.show')
                                <a href="{{ route('admin.client.lead_source.show', [$client, $mapping->lead_source]) }}">{{ $mapping->lead_source->name }}</a>
                            @else
                                {{ $mapping->lead_source->name }}
                            @endcan
                        </td>
                    </tr>

                    {{-- Lead Destination --}}

                    <tr>
                        <th scope="row">Lead Destination</th>
                        <td>
                            @can('client.lead_destination.show')
                                <a href="{{ route('admin.client.lead_destination.show', [$client, $mapping->lead_destination]) }}">{{ $mapping->lead_destination->name }}</a>
                            @else
                                {{ $mapping->lead_destination->name }}
                            @endcan
                        </td>
                    </tr>

                    {{-- TODO --}}

                </tbody>
            </table>
        </div>

    @endcomponent
    
@endsection
