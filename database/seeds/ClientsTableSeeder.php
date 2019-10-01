<?php

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        Client::create([
            'name' => 'Test Client',
            'is_active' => true,
            'notes' => ''
        ]);

    }

}
