<?php

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
        
        DB::table('gravity_forms_source_configs')->insert([
            // this space intentionally left blank
        ]);

    }

}
