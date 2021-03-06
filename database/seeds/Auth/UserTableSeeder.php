<?php

use App\Models\Auth\User;
use Illuminate\Database\Seeder;

/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
{
    
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {

        $this->disableForeignKeys();

        // Add the master administrator, user id of 1
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'travis@immersionactive.com',
            'password' => 'password',
            'confirmation_code' => md5(uniqid(mt_rand(), true)),
            'confirmed' => true,
        ]);

        User::create([
            'first_name' => 'Strategist',
            'last_name' => 'Admin',
            'email' => 'travis+strategist@immersionactive.com',
            'password' => 'password',
            'confirmation_code' => md5(uniqid(mt_rand(), true)),
            'confirmed' => true,
        ]);

        $this->enableForeignKeys();

    }

}
