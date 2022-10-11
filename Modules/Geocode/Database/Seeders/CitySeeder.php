<?php

namespace Modules\Geocode\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Geocode\Entities\City;

/**
 * Class CityTableSeeder.
 */
class CitySeeder extends Seeder
{
    /**
     * Run the database seed.
     */
    public function run()
    {

        // Add cities (Gaza,Al-Minia) 
        City::create([
            'code' => '457',
            'name' => 'Gaza',
            'country_id'=> 1 ,
            'status' => 1
        ]);
        City::create([
            'code' => '5414',
            'name' => 'Al-Minia',
            'country_id'=> 2 ,
            'status' => 1
        ]);

    }
}
