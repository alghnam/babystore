<?php

namespace Modules\BuyingSystemMount\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\BuyingSystemMount\Entities\BuyingSystemMount;

/**
 * Class BuyingSystemMountTableSeeder.
 */
class BuyingSystemMountSeeder extends Seeder
{

    /**
     * Run the database seed.
     */
    public function run()
    {

        // Add BuyingSystemMount
        BuyingSystemMount::create([
            'mount' => '100000'
        ]);

    }
}
