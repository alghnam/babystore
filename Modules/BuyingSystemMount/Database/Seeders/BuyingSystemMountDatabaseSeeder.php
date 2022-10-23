<?php

namespace Modules\BuyingSystemMount\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\BuyingSystemMount\Database\Seeders\BuyingSystemMountSeeder;

class BuyingSystemMountDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(BuyingSystemMountSeeder::class);
    }
}
