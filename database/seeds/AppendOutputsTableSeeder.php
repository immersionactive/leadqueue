<?php

use App\Models\AppendOutput;
use Illuminate\Database\Seeder;

class AppendOutputsTableSeeder extends Seeder
{

    public function run()
    {
        
        // TODO: email and phone, if we can find them

        AppendOutput::create([
            'slug' => 'estimated_income_min',
            'label' => 'Estimated Income (Minimum)',
            'bundle' => 'investmentsandassets',
            'property' => 'estimatedIncomeMin'
        ]);

        AppendOutput::create([
            'slug' => 'estimated_income_max',
            'label' => 'Estimated Income (Maximum)',
            'bundle' => 'investmentsandassets',
            'property' => 'estimatedIncomeMax'
        ]);

        AppendOutput::create([
            'slug' => 'recent_home_buyer',
            'label' => 'Recent Home Buyer?',
            'bundle' => 'mortgagesandloans',
            'property' => 'recentHomeBuyer'
        ]);

        AppendOutput::create([
            'slug' => 'gender',
            'label' => 'Gender',
            'bundle' => 'basicdemographics',
            'property' => 'gender'
        ]);

        AppendOutput::create([
            'slug' => 'age',
            'label' => 'Age',
            'bundle' => 'basicdemographics',
            'property' => 'age'
        ]);

    }

}
