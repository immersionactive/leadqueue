<?php

namespace App\Console\Commands;

use App\LeadProcessor;
use Artisan;
use App\Models\Lead;
use Illuminate\Console\Command;

class LeadsProcess extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:process-all';

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
    public function handle()
    {
        
        $leads = Lead::where('status', '<>', 'complete')->get();
        $this->info($leads->count() . ' incomplete lead(s) found.');

        if ($leads->count() === 0) {
            // We're done here; quit with an exit code of 0
            return 0;
        }

        try {
            $processor = new LeadProcessor();
        } catch (\Exception $e) {
            // TODO: why isn't this catching exceptions?
            $this->error('Couldn\'t construct a LeadProcessor: ' . $e->getMessage());
            return 1;
        }

        foreach ($leads as $lead) {
            
            try {
                $processor->process($lead);
            } catch (\Exception $e) {
                // TODO: log and/or report to console
                throw $e; // but for now, we'll just re-throw it
            }

        }

        return 0;

    }

}
