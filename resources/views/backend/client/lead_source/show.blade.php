@extends('backend.layouts.app')

@section('title', $lead_source->name . ' < Lead Sources < ' . $client->name);

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
                        @can('client.lead_source.edit', $client)
                            <a href="{{ route('admin.client.lead_source.edit', [$client, $lead_source]) }}" class="btn btn-success ml-1" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                        @endcan
                    </div>

                </div>

            </div>

            <div class="row mt-4">
                <div class="col">

                    @include('backend.client.includes.tabs', ['active_tab' => 'lead_sources', 'client' => $client])

                    <div class="tab-content">
                        <div class="tab-pane active" role="tabpanel" aria-expanded="true">

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tbody>

                                        {{-- Name --}}

                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $lead_source->name }}</td>
                                        </tr>

                                        {{-- Active --}}

                                        <tr>
                                            <th>Active</th>
                                            <td>
                                                @include('backend.includes.partials.yn-badge', ['active' => $lead_source->is_active])
                                            </td>
                                        </tr>

                                        {{-- Notes --}}

                                        <tr>
                                            <th>Notes</th>
                                            <td>{!! nl2br(e($lead_source->notes)) !!}</td>
                                        </tr>

                                        @include($source_config_type_classname::getShowView())

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
