<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\Auth\Database\Seeders\AuthDatabaseSeeder;
use Modules\Geocode\Database\Seeders\GeocodeDatabaseSeeder;
use Modules\Profile\Database\Seeders\ProfileDatabaseSeeder;
use Modules\Category\Database\Seeders\CategoryDatabaseSeeder;
use Modules\Product\Database\Seeders\ProductDatabaseSeeder;
use Modules\ProductAttribute\Database\Seeders\ProductAttributeDatabaseSeeder;
use Modules\Review\Database\Seeders\ReviewDatabaseSeeder;
use Modules\Favorite\Database\Seeders\FavoriteDatabaseSeeder;
use Database\Seeders\ChatRoomSeeder;
use Modules\Chat\Database\Seeders\ChatDatabaseSeeder;
use Modules\Movement\Database\Seeders\MovementDatabaseSeeder;
use Modules\Order\Database\Seeders\OrderDatabaseSeeder;
use Modules\Payment\Database\Seeders\PaymentDatabaseSeeder;
use Modules\Reward\Database\Seeders\RewardDatabaseSeeder;
use Modules\Rule\Database\Seeders\RuleDatabaseSeeder;
use Modules\Search\Database\Seeders\SearchDatabaseSeeder;
use Modules\Service\Database\Seeders\ServiceDatabaseSeeder;
use Modules\SimilarProduct\Database\Seeders\SimilarProductDatabaseSeeder;
use Modules\StorageDetail\Database\Seeders\StorageDetailDatabaseSeeder;
use Modules\SystemReview\Database\Seeders\SystemReviewDatabaseSeeder;
use Modules\View\Database\Seeders\ViewDatabaseSeeder;
use Modules\Wallet\Database\Seeders\WalletDatabaseSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(GeocodeDatabaseSeeder::class);
        $this->call(AuthDatabaseSeeder::class);
        $this->call(CategoryDatabaseSeeder::class);
        $this->call(ProductDatabaseSeeder::class);
        $this->call(ProductAttributeDatabaseSeeder::class);

        $this->call(FavoriteDatabaseSeeder::class);
        $this->call(ReviewDatabaseSeeder::class);
            $this->call(MovementDatabaseSeeder::class);
        $this->call(OrderDatabaseSeeder::class);
        $this->call(PaymentDatabaseSeeder::class);
        $this->call(RewardDatabaseSeeder::class);
        $this->call(RuleDatabaseSeeder::class);
        $this->call(SearchDatabaseSeeder::class);
        $this->call(ServiceDatabaseSeeder::class);
        $this->call(SimilarProductDatabaseSeeder::class);
        $this->call(StorageDetailDatabaseSeeder::class);
        $this->call(SystemReviewDatabaseSeeder::class);
        $this->call(ViewDatabaseSeeder::class);
        $this->call(WalletDatabaseSeeder::class);
        $this->call(ChatDatabaseSeeder::class);

       $this->call([
            ChatRoomSeeder::class    
           ]);
    }
}
