@extends('backend.layouts.app')

@section('title', $mapping->name . ': Fields')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.mapping.components.tabbox', ['client' => $client, 'mappings' => $mappings, 'active_mapping_id' => $mapping->id])

        @can('client.mapping.mapping_field.create')
            <a href="{{ route('admin.client.mapping.mapping_field.create', [$client, $mapping]) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.create')" class="btn btn-success">
                <i class="fas fa-plus-circle"></i>
            </a>
        @endcan

        @if ($mapping_fields->count() === 0)

            <p>There are no fields for this mapping.</p>

        @else

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Field</th>
                            <th scope="col">@lang('labels.general.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mapping_fields as $mapping_field)
                            <tr>
                                <td>TODO</td>
                                <td>


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
