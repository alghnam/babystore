<?php

namespace Modules\Favorite\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class FavoriteDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(FavoriteSeeder::class);//run FavoriteSeeder seeder that it in module Favorite
    }
}

