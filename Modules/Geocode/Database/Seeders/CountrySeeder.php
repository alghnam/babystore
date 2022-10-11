<?php

namespace Modules\Geocode\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Geocode\Entities\Country;

/**
 * Class CountryTableSeeder.
 */
class CountrySeeder extends Seeder
{

    /**
     * Run the database seed.
     */
    public function run()
    {

        // Add countries (palestine,egypt) 
        Country::create([
            'code' => '12454',
            'name' => 'Palestine',
            'status' => 1
        ]);
        Country::create([
            'code' => '14574',
            'name' => 'Egypt',
            'status' => 1
        ]);

    }
}
