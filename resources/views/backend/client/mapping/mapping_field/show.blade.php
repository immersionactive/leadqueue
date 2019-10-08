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

        <h2>This show view isn't implemented yet...</h2>

    @endcomponent

@endsection
