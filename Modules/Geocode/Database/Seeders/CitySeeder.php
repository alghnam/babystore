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
            'code' => '45777',
            'name' => 'الصالحية',
            'country_id'=> 1 ,
            'status' => 1
        ]);
        City::create([
            'code' => '45744',
            'name' => 'غزة',
            'country_id'=> 2 ,
            'status' => 1
        ]);
        City::create([
            'code' => '5414',
            'name' => 'المنيا',
            'country_id'=> 3 ,
            'status' => 1
        ]);

    }
}
