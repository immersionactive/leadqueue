@extends('backend.layouts.app')

@section('title', 'TODO')

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

            </div>

            <div class="row mt-4">
                <div class="col">

                    @include('backend.client.includes.tabs', ['active_tab' => 'lead_sources', 'client' => $client])

                    <div class="tab-content">
                        <div class="tab-pane active" role="tabpanel" aria-expanded="true">

                            <h2 class="h4">{{ $lead_source->name }}: Leads Received: Lead {{ $lead->id }} Detail</h2>

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tbody>

                                        <tr>
                                            <th scope="row">ID</th>
                                            <td>{{ $lead->id }}</td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Date/Time Received</th>
                                            <td>{{ timezone()->convertToLocal($lead->created_at) }} ({{ $lead->created_at->diffForHumans() }})</td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Request Method</th>
                                            <td>{{ $lead->request_method }}</td>
                                        </tr>

                                        <tr>
                                            <th scope="row">Request Headers</th>
                                            <td>
                                                <pre>
                                                    @request_headers_json($lead->request_headers_json)
                                                </pre>
                                            </td>
                                        </tr>

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
