{{-- Client ID --}}

<tr>
    <th scope="row">Client ID</th>
    <td>{{ $lead_destination->destination_config->client_id }}</td>
</tr>

{{-- Client Secret --}}

<tr>
    <th scope="row">Client Secret</th>
    <td>&bull;&bull;&bull;&bull;&bull;</td>
</tr>

{{-- Authorize --}}

<tr>
    <th scope="row">Authorize</th>
    <td>

        <p>TODO: explain this</p>

        @if ($lead_destination->destination_config->access_token)

            <p>Already authorized.</p>

            <a href="{{ route('admin.infusionsoft.authorize.begin', [$lead_destination]) }}" class="btn btn-primary">
                <i class="fas fa-redo-alt"></i> Reauthorize
            </a>

            {{-- TODO: The modal that appears when you click this popup shows generic "Delete" language, for some reason --}}
            <a href="{{ route('admin.infusionsoft.authorize.deauthorize', [$lead_destination]) }}" class="btn btn-danger"
                data-method="post"
                data-trans-button-cancel="Cancel"
                data-trans-button-confirm="Deauthorize"
                data-trans-title="Are you sure you want to deauthorize this Lead Destination?"
            >
                <i class="fas fa-sign-out-alt"></i> Deauthorize
            </a>

            <p>TODO: Add a "Test" button here?</p>

        @else

            <p class="text-danger"><strong>Not yet authorized!</strong></p>

            <a href="{{ route('admin.infusionsoft.authorize.begin', [$lead_destination]) }}" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Authorize
            </a>

        @endif

    </td>
</tr>
