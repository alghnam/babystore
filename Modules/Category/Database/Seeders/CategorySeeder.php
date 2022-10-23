<?php

namespace Modules\Category\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Category\Entities\Category;

/**
 * Class CategoryTableSeeder.
 */
class CategorySeeder extends Seeder
{

    /**
     * Run the database seed.
     */
    public function run()
    {

        // Add categories (Babies,Children) 
        Category::create([
            'name' => 'Babies',
            'parent_id' => null,
            'status' => 1
        ]);
        Category::create([
            'name' => 'Boys',
            'parent_id' => null,
            'status' => 1
        ]);
        Category::create([
            'name' => 'Girls',
            'parent_id' => null,
            'status' => 1
        ]);

        Category::create([
            'name' => 'T-shirts',
            'parent_id' => 1,
            'status' => 1
        ]);
        Category::create([
            'name' => 'Shoes',
            'parent_id' => 2,
            'status' => 2
        ]);
        Category::create([
            'name' => 'Jackets',
            'parent_id' => 3,
            'status' => 3
        ]);

    }
}
