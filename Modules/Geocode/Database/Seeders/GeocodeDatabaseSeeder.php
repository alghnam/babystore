<?php

namespace Modules\Geocode\Database\Seeders;
use Modules\Geocode\Database\Seeders\CountrySeeder;
use Modules\Geocode\Database\Seeders\CitySeeder;
use Modules\Geocode\Database\Seeders\TownSeeder;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class GeocodeDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(CountrySeeder::class);
        $this->call(CitySeeder::class);
        $this->call(TownSeeder::class);
    }
}
