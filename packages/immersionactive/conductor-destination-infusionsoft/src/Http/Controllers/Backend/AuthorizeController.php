<?php

namespace ImmersionActive\Conductor\Destinations\Infusionsoft\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LeadDestination;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illiminate\Support\Facades\Log;
use Infusionsoft\Infusionsoft;

class AuthorizeController extends Controller
{

    public function begin(LeadDestination $lead_destination)
    {

        // TODO: check user permissions
        // $this->authorize('client.lead_destination.edit');

        $infusionsoft = $this->buildInfusionsoftClient($lead_destination);
        $auth_url = $infusionsoft->getAuthorizationUrl();

        return redirect()->away($auth_url);

    }

    /**
     * Infusionsoft will redirect the user back to this URL after a successful
     * authorization.
     * 
     * Possible querystring vars:
     * 
     * - state
     * - code (only on success)
     * - scope (only on success)
     * - error (only on failure)
     * - error_uri (only on failure)
     * - error_description (only on failure)
     */
    public function callback(Request $request, LeadDestination $lead_destination)
    {

        // TODO: check user permissions
        // $this->authorize('client.lead_destination.edit');

        // Check for error state

        $error = $request->query('error');

        if (mb_strlen($error)) {

            $error_uri = $request->query('error_uri');
            $error_description = $request->query('error_description');
            $message = 'Authorization failed: "' . htmlspecialchars($error);
            if (mb_strlen($error_description)) {
                $message .= ': ' . $error_description;
            }
            $message .= '".';
            if (mb_strlen($error_uri)) {
                $message .= ' More information may be available here: <a rel="external" href="' . htmlspecialchars($error_uri) . '">' . htmlspecialchars($error_uri) . '</a>';
            }
            $request->session()->flash('flash_danger', $message);

        } else {
            
            $code = $request->query('code');
            $scope = $request->query('scope');

            if (!mb_strlen($code)) {
                throw new \Exception('The callback URL does not contain a querystring parameter named "code".');
            }

            // Now we need to exchange the code for an actual token
   
            $infusionsoft = $this->buildInfusionsoftClient($lead_destination);
            $token = $infusionsoft->requestAccessToken($_GET['code']);

            $lead_destination->destination_config->access_token = $token->accessToken;
            $lead_destination->destination_config->access_token_expires_at = Carbon::createFromTimestampUTC($token->endOfLife);
            $lead_destination->destination_config->refresh_token = $token->refreshToken;
            $lead_destination->destination_config->save();

            $request->session()->flash('flash_success', 'The Lead Destination was successfully authorized to Infusionsoft.');

            $this->log('info', 'New Infusionsoft OAuth2 tokens obtained for Lead Destination ' . $lead_destination->id . ' (' . $lead_destination->name . ') for client ' . $lead_destination->client->id . ' (' . $lead_destination->client->name . ')');

        }

        return redirect()->route('admin.client.lead_destination.show', [$lead_destination->client_id, $lead_destination]);

    }

    public function deauthorize(LeadDestination $lead_destination)
    {

        // TODO: check user permissions
        // $this->authorize('client.lead_destination.edit');

        $lead_destination->destination_config->access_token = null;
        $lead_destination->destination_config->access_token_expires_at = null;
        $lead_destination->destination_config->refresh_token = null;
        $lead_destination->destination_config->save();

        $this->log('info', 'Deleted the stored Infusionsoft OAuth2 tokens for Lead Destination ' . $lead_destination->id . ' (' . $lead_destination->name . ') for client ' . $lead_destination->client->id . ' (' . $lead_destination->client->name . ')');

        return redirect()->route('admin.client.lead_destination.show', [$lead_destination->client_id, $lead_destination])->with('success', 'Deauthorized.');

    }

    protected function buildInfusionsoftClient(LeadDestination $lead_destination): Infusionsoft
    {
        return new Infusionsoft([
            'clientId' => $lead_destination->destination_config->client_id,
            'clientSecret' => $lead_destination->destination_config->client_secret,
            'redirectUri' => route('admin.infusionsoft.authorize.callback', [$lead_destination->id])
        ]);
    }

}
