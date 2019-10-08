@extends('backend.layouts.app')

@section('title', $mapping->name . ': Fields')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.mapping.components.tabbox', ['client' => $client, 'mappings' => $mappings, 'active_mapping_id' => $mapping->id])

        <div class="row mb-4">

            <div class="col-md-6">
                <h4>{{ $mapping->name }}: Fields</h4>
            </div>

            @canany(['client.mapping.mapping_field.edit'])
                <div class="col-md-6 pull-right">

                    <div class="btn-group float-right" role="group" aria-label="TODO">

                        @can('client.mapping.mapping_field.edit')
                            <a href="{{ route('admin.client.mapping.mapping_field.edit', [$client, $mapping, null]) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.create')" class="btn btn-success">
                                <i class="fas fa-plus-circle"></i>
                            </a>
                        @endcan

                    </div>

                </div>
            @endcanany

        </div>

        @if ($mapping_fields->count() === 0)

            <p>There are no fields for this mapping.</p>

        @else

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Source Field</th>
                            <th scope="col">Destination Field</th>
                            <th scope="col">@lang('labels.general.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mapping_fields as $mapping_field)
                            <tr>
                                <td>
                                    {{-- TODO: this is really inefficient. it has to run two additional render-time queries for each iteration of the loop (one to load the lead_source, and another to load the lead_destination) --}}
                                    @if (array_key_exists($mapping->lead_source->source_config_type, $source_config_type_classnames_by_model_classname))
                                        @include($source_config_type_classnames_by_model_classname[$mapping->lead_source->source_config_type]::getSourceFieldConfigSummaryView(), ['source_field_config' => $mapping_field->source_field_config])
                                    @else
                                        <span class="text-danger">Unknown ({{ $mapping->lead_source->source_config_type }})</span>
                                    @endif
                                </td>
                                <td>
                                    @if (array_key_exists($mapping->lead_destination->destination_config_type, $destination_config_type_classnames_by_model_classname))
                                        @include($destination_config_type_classnames_by_model_classname[$mapping->lead_destination->destination_config_type]::getDestinationFieldConfigSummaryView(), ['destination_field_config' => $mapping_field->destination_field_config])
                                    @else
                                        <span class="text-danger">Unknown ({{ $mapping->lead_destination->destination_config_type }})</span>
                                    @endif
                                </td>
                                <td>

                                    <div class="btn-group" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">

                                        @can('client.mapping.mapping_field.show')
                                            <a href="{{ route('admin.client.mapping.mapping_field.show', [$client, $mapping, $mapping_field]) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.view')" class="btn btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan

                                        @can('client.mapping.mapping_field.edit')
                                            <a href="{{ route('admin.client.mapping.mapping_field.edit', [$client, $mapping, $mapping_field]) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.edit')" class="btn btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('client.mapping.mapping_field.destroy')
                                            <a href="{{ route('admin.client.mapping.mapping_field.destroy', [$client, $mapping, $mapping_field]) }}"
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

            {{ $mapping_fields->links() }}

        @endif

    @endcomponent
    
@endsection
