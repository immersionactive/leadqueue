@extends('backend.layouts.app')

@section('title', $mapping->name . ' < Mappings < ' . $client->name);

@section('breadcrumb-links')
    {{-- @include('backend.client.includes.breadcrumb-links') --}}
@endsection

@section('content')

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
                        @can('client.mapping.edit', $client)
                            <a href="{{ route('admin.client.mapping.edit', [$client, $mapping]) }}" class="btn btn-success ml-1" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
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

                                        {{-- Lead Source --}}

                                        <tr>
                                            <th scope="row">Lead Source</th>
                                            <td>
                                                @can('client.lead_source.show')
                                                    <a href="{{ route('admin.client.lead_source.show', [$client, $mapping->lead_source]) }}">{{ $mapping->lead_source->name }}</a>
                                                @else
                                                    {{ $mapping->lead_source->name }}
                                                @endcan
                                                @include('backend.includes.partials.yn-badge', ['active' => $mapping->lead_source->is_active, 'yes_text' => 'Active', 'no_text' => 'Inactive'])
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
                                                @include('backend.includes.partials.yn-badge', ['active' => $mapping->lead_destination->is_active, 'yes_text' => 'Active', 'no_text' => 'Inactive'])
                                            </td>
                                        </tr>

                                        {{-- TODO --}}

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div> <!-- .tab-content -->

                </div>
            </div>

        </div> <!-- .card-body -->

    </div> <!-- .card -->
    
@endsection
