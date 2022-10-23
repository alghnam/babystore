<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
			$table->string('locale')->default(config('app.locale'));
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('CASCADE')->onDelete('CASCADE');
                        $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->foreign('sub_category_id')->references('id')->on('sub_categoriess')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->longText('description')->nullable();
            $table->integer('quantity')->default(0);
			$table->float('original_price')->default(0);
			$table->float('price_discount_ends')->default(0);


            $table->tinyInteger('is_offers')->default(0);
            $table->integer('orders_counter')->default(0);

            $table->tinyInteger('status')->default(1);
            $table->date('deleted_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
