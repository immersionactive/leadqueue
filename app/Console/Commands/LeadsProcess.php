<?php

namespace App\Console\Commands;

use Log;
use Notification;
use App\AppendProcessor;
use App\DestinationConfigTypeRegistry;
use App\DestinationProcessor;
use App\Models\Lead;
use App\Notifications\LeadAppendFailed;
use App\Notifications\LeadDestinationFailed;
use Illuminate\Console\Command;
use Illuminate\Notifications\Notification as BaseNotification;
use Spatie\Permission\Guard;

class LeadsProcess extends Command
{

    protected $superusers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all leads with a non-complete status.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(DestinationConfigTypeRegistry $destination_config_type_registry)
    {

        $leads = Lead::whereIn('status', ['new', 'appended'])->get();
        $this->info($leads->count() . ' incomplete lead(s) found.');

        if ($leads->count() === 0) {
            // We're done here; quit with an exit code of 0
            return 0;
        }

        $append_processor = $this->getAppendProcessor();
        $destination_processor = new DestinationProcessor($destination_config_type_registry);

        foreach ($leads as $lead) {

            // Don't do anything with leads whose mappings are inactive.
            if (!$lead->mapping->is_active) {
                continue;
            }

            // Has this lead been appended yet? If not, try to append it.
            $this->info('Processing lead ' . $lead->id . '...');

            if ($lead->status === 'new') {
                try {
                    $append_processor->append($lead);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    $this->error('Lead ' . $lead->id . ' failed append: ' . $e->getMessage());
                    $this->sendLeadAppendFailedNotification($lead, $e);
                }
            }

            // Has this lead been appended, but not yet inserted into the
            // destination system? (This could've happened just above.) If so,
            // try to insert it into the destination system.
            if ($lead->status === 'appended') {
                try {
                    $destination_processor->insert($lead);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    $this->error('Lead ' . $lead->id . ' failed destination insertion: ' . $e->getMessage());
                    $this->sendLeadDestinationFailedNotification($lead, $e);
                }
            }

        }

        $this->info('Lead processing complete.');

        return 0;

    }

    protected function getAppendProcessor(): AppendProcessor
    {
        try {
            $append_processor = new AppendProcessor();
        } catch (\Exception $e) {
            // TODO: why isn't this catching exceptions?
            $this->error('Couldn\'t construct an AppendProcessor: ' . $e->getMessage());
            return 1;
        }
        return $append_processor;
    }

    protected function sendLeadAppendFailedNotification(Lead $lead, \Exception $exception): void
    {
        $notification = new LeadAppendFailed($lead, $exception);
        $recipient = env('LEAD_ERROR_NOTIFY_EMAIL', 'developer@immersionactive.com');
        Notification::route('mail', $recipient)->notify($notification);
    }

    protected function sendLeadDestinationFailedNotification(Lead $lead, \Exception $exception): void
    {
        $notification = new LeadDestinationFailed($lead, $exception);
        $recipient = env('LEAD_ERROR_NOTIFY_EMAIL', 'developer@immersionactive.com');
        Notification::route('mail', $recipient)->notify($notification);
    }


}
