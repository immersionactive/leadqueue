<?php

use App\Models\AppendOutput;
use Illuminate\Database\Seeder;

class AppendOutputsTableSeeder extends Seeder
{

    public function run()
    {
        
        // TODO: email and phone, if we can find them

        AppendOutput::create([
            'path' => 'person.investmentsandassets.estimatedIncomeMin',
            'label' => 'Estimated Income (Minimum)'
        ]);

        AppendOutput::create([
            'path' => 'person.investmentsandassets.estimatedIncomeMax',
            'label' => 'Estimated Income (Maximum)'
        ]);

        AppendOutput::create([
            'path' => 'person.mortgagesandloans.recentHomeBuyer',
            'label' => 'Recent Home Buyer?',
            // 'translator' => 'yesno'
        ]);

        AppendOutput::create([
            'path' => 'person.basicdemographics.gender',
            'label' => 'Gender'
        ]);

        AppendOutput::create([
            'path' => 'person.basicdemographics.age',
            'label' => 'Age'
        ]);

    }

}
