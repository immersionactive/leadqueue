@extends('backend.layouts.app')

@section('title', $mapping->name . ': Fields')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.mapping.components.tabbox', ['client' => $client, 'mappings' => $mappings, 'active_mapping_id' => $mapping->id])

        <div class="table-responsive">
            <table class="table">
                <tbody>
                    
                    {{-- ID --}}

                    <tr>
                        <th scope="row">Lead ID</th>
                        <td>{{ $lead->id }}</td>
                    </tr>

                    {{-- Received --}}

                    <tr>
                        <th scope="row">Received</th>
                        <td>
                            {{ timezone()->convertToLocal($lead->created_at) }}<br>
                            <small>({{ $lead->created_at->diffForHumans() }})</small>
                        </td>
                    </tr>

                    {{-- Inputs --}}

                    <tr>
                        <th scope="row">Input Received</th>
                        <td>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        @can ('client.mapping.mapping_field.show')
                                            <th scope="col">
                                                Mapping Field
                                            </th>
                                        @endcan
                                        <th scope="col">Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lead->lead_inputs as $lead_input)
                                        <tr>
                                            <td>{{ $lead_input->id }}</td>
                                            @can ('client.mapping.mapping_field.show')
                                                <td>
                                                    <a href="{{ route('admin.client.mapping.mapping_field.show', [$client, $lead->mapping_id, $lead_input->mapping_field_id]) }}">
                                                        @include($source_config_type_classname::getSourceFieldConfigSummaryView(), ['source_field_config' => $lead_input->mapping_field->source_field_config])
                                                    </a>
                                                </td>
                                            @endcan
                                            <td>{{ $lead_input->value }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    @endcomponent
    
@endsection
