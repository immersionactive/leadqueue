@extends('backend.layouts.app')

@section('title', $mapping->name . ': Fields')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.mapping.components.tabbox', ['client' => $client, 'mappings' => $mappings, 'active_mapping_id' => $mapping->id])

        <div class="row mb-4">

            <div class="col-md-6">
                <h4>{{ $mapping->name }}: Fields: View Field</h4>
            </div>

            @canany(['client.mapping.mapping_field.edit'])
                <div class="col-md-6 pull-right">

                    <div class="btn-group float-right" role="group" aria-label="TODO">

                        @can('client.mapping.mapping_field.edit')
                            <a href="{{ route('admin.client.mapping.mapping_field.edit', [$client, $mapping, $mapping_field]) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.edit')" class="btn btn-success">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endcan

                    </div>

                </div>
            @endcanany

        </div>

        <div class="card">

            <div class="card-header">
                General
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table">
                        <tbody>

                            {{-- Name --}}

                            <tr>
                                <th scope="row">Name</th>
                                <td>{{ $mapping_field->name }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-6">

                <div class="card">

                    <div class="card-header">
                        <strong>Source Field</strong> (from {{ $source_config_type_classname::getName() }})
                    </div> <!-- .card-header -->

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>

                                    {{-- type-specific fields --}}

                                    @include($source_config_type_classname::getSourceFieldConfigShowView(), ['source_field_config' => $mapping_field->source_field_config])

                                    {{-- Append Input --}}

                                    <tr>
                                        <th scope="row">Append Input</th>
                                        <td>
                                            @if ($mapping_field->append_input)
                                                {{ $mapping_field->append_input->name }}
                                            @else
                                                &mdash;
                                            @endif
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                    </div> <!-- .card-body -->

                </div> <!-- .card -->

            </div> <!-- .col -->

            <div class="col-md-6">

                <div class="card">

                    <div class="card-header">
                        <strong>Destination Field</strong> (in {{ $destination_config_type_classname::getName() }})
                    </div> <!-- .card-header -->

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>

                                    {{-- type-specific fields --}}

                                    @include($destination_config_type_classname::getDestinationFieldConfigShowView(), ['destination_field_config' => $mapping_field->destination_field_config])

                                </tbody>
                            </table>
                        </div>

                    </div> <!-- .card-body -->

                </div> <!-- .card -->

            </div> <!-- .col -->

        </div> <!-- .row -->

    @endcomponent

@endsection
