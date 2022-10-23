<?php

namespace Modules\Geocode\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Geocode\Entities\Town;

/**
 * Class TownTableSeeder.
 */
class TownSeeder extends Seeder
{
    /**
     * Run the database seed.
     */
    public function run()
    {

        // Add cities (Al-Naser,Bany-Mazar) 
        Town::create([
            'code' => '457',
            'name' => 'محافظة العاصمة',
            'city_id'=> 1 ,
            'status' => 1
        ]);
        Town::create([
            'code' => '457',
            'name' => 'النصر',
            'city_id'=> 2 ,
            'status' => 1
        ]);
        Town::create([
            'code' => '5414',
            'name' => 'بني مزار',
            'city_id'=> 2 ,
            'status' => 1
        ]);

    }
}
