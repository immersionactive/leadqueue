<?php

use App\Models\AppendInput;
use Illuminate\Database\Seeder;

class AppendInputsTableSeeder extends Seeder
{

    public function run()
    {
        
        AppendInput::create([
            'property' => 'FirstName',
            'name' => 'First Name'
        ]);

        AppendInput::create([
            'property' => 'MiddleName',
            'name' => 'Middle Name or Initial'
        ]);

        AppendInput::create([
            'property' => 'LastName',
            'name' => 'Last Name'
        ]);

        AppendInput::create([
            'property' => 'generationalSuffix',
            'name' => 'Suffix (Jr., Sr., etc.)'
        ]);

        AppendInput::create([
            'property' => 'Email',
            'name' => 'Email Address'
        ]);

        AppendInput::create([
            'property' => 'Phone',
            'name' => 'Phone Number'
        ]);

        AppendInput::create([
            'property' => 'YearOfBirth',
            'name' => 'Year of Birth'
        ]);

        AppendInput::create([
            'property' => 'streetAddress',
            'name' => 'Street Address'
        ]);

        AppendInput::create([
            'property' => 'City',
            'name' => 'City'
        ]);

        AppendInput::create([
            'property' => 'State',
            'name' => 'State'
        ]);

        AppendInput::create([
            'property' => 'ZipCode',
            'name' => 'ZIP Code'
        ]);

    }

}
