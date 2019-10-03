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

                @include('backend.client.includes.tabs', ['active_tab' => 'lead_destinations', 'client' => $client])

                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel" aria-expanded="true">

                        @component('backend.components.index-detail')

                            @slot('sidebar')
                                @include('backend.client.lead_destination.includes.lead-destination-index', ['lead_destinations' => $lead_destinations, 'active_lead_destination_id' => $active_lead_destination_id ?? null, 'destination_config_type_classnames' => $destination_config_type_classnames])
                            @endslot

                            <div class="card">
                                <div class="card-body">

                                    {{ $slot }}

                                </div> <!-- .card-body -->
                            </div> <!-- .card -->

                        @endcomponent

                    </div> <!-- .tab-pane -->
                </div> <!-- .tab-content -->

            </div> <!-- .col -->
        </div> <!-- .row -->

    </div> <!-- .card-body -->

</div> <!-- .card -->
