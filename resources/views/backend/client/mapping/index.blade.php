@extends('backend.layouts.app')

@section('title', $client->name . ': Mappings')

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

    @component('backend.client.mapping.components.tabbox', ['client' => $client, 'mappings' => $mappings])

        <p class="mb-0">Please select a mapping to the left.</p>

    @endcomponent

    {{--
    <div class="card">

        <div class="card-body">

            <div class="row">

                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        {{ $client->name }}
                    </h4>
                </div>

                <div class="col-sm-7 pull-right">
                    
                    <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                        @can('client.mapping.create')
                            <a href="{{ route('admin.client.mapping.create', $client) }}" class="btn btn-success ml-1" data-toggle="tooltip" title="@lang('labels.general.create_new')"><i class="fas fa-plus-circle"></i></a>
                        @endcan
                    </div>

                </div>

            </div>

            <div class="row mt-4">
                <div class="col">

                    @include('backend.client.includes.tabs', ['active_tab' => 'mappings', 'client' => $client])

                    <div class="tab-content">
                        <div class="tab-pane active" role="tabpanel" aria-expanded="true">

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Mapping</th>
                                            <th scope="col">Active</th>
                                            <th scope="col">@lang('labels.general.actions')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mappings as $mapping)
                                            <tr>
                                                <td>{{ $mapping->name }}</td>
                                                <td>
                                                    @include('backend.includes.partials.yn-badge', ['active' => $mapping->is_active])
                                                </td>
                                                <td>

                                                    <div class="btn-group" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">

                                                        @can('client.mapping.show')
                                                            <a href="{{ route('admin.client.mapping.show', [$client, $mapping]) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.view')" class="btn btn-info">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endcan

                                                        @can('client.mapping.update')
                                                            <a href="{{ route('admin.client.mapping.edit', [$client, $mapping]) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.edit')" class="btn btn-primary">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endcan

                                                        @can('client.mapping.mapping_field.index')
                                                            <a href="{{ route('admin.client.mapping.mapping_field.index', [$client, $mapping]) }}" data-toggle="tooltip" data-placement="top" title="View Fields" class="btn btn-info">
                                                                <i class="fas fa-list"></i>
                                                            </a>
                                                        @endcan

                                                        @can('client.mapping.destroy')
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

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $mappings->links() }}

                        </div>
                    </div> <!-- .tab-content -->

                </div>
            </div>

        </div> <!-- .card-body -->

    </div> <!-- .card -->
    --}}
    
@endsection
