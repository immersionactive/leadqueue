<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadSourcesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('lead_sources')->insert([
            'client_id' => 1,
            'name' => 'Test GF Source',
            'is_active' => true,
            'notes' => '',
            'source_config_id' => 1,
            'source_config_type' => 'ImmersionActive\\LeadQueueGravityFormsSource\\Models\\GravityFormsSourceConfig',
        ]);

    }

}
