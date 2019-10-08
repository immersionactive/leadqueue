<?php

use App\Models\AppendProperty;
use Illuminate\Database\Seeder;

class AppendPropertiesTableSeeder extends Seeder
{

    public function run()
    {
        
        // TODO: email and phone, if we can find them

        AppendProperty::create([
            'slug' => 'estimated_income_min',
            'label' => 'Estimated Income (Minimum)',
            'bundle' => 'investmentsandassets',
            'property' => 'estimatedIncomeMin'
        ]);

        AppendProperty::create([
            'slug' => 'estimated_income_max',
            'label' => 'Estimated Income (Maximum)',
            'bundle' => 'investmentsandassets',
            'property' => 'estimatedIncomeMax'
        ]);

        AppendProperty::create([
            'slug' => 'recent_home_buyer',
            'label' => 'Recent Home Buyer?',
            'bundle' => 'mortgagesandloans',
            'property' => 'recentHomeBuyer'
        ]);

        AppendProperty::create([
            'slug' => 'gender',
            'label' => 'Gender',
            'bundle' => 'basicdemographics',
            'property' => 'gender'
        ]);

        AppendProperty::create([
            'slug' => 'age',
            'label' => 'Age',
            'bundle' => 'basicdemographics',
            'property' => 'age'
        ]);

    }

}
