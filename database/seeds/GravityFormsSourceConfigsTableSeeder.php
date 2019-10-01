<?php

use ImmersionActive\LeadQueueGravityFormsSource\Models\GravityFormsSourceConfig;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GravityFormsSourceConfigsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        GravityFormsSourceConfig::create([
            // this space intentionally left blank
        ]);

    }

}
