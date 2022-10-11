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
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->string('description')->nullable();
            $table->integer('quantity');
			$table->integer('counter_discount')->nullable();
			$table->float('original_price')->nullable();
			$table->float('price_after_discount')->nullable();
			$table->float('price_discount_ends');


            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('is_offers')->default(0);
            $table->tinyInteger('the_best')->default(0);
            $table->tinyInteger('the_more_sale')->default(0);
            $table->tinyInteger('feature')->default(0);
            $table->tinyInteger('popular')->default(0);
            $table->tinyInteger('modern')->default(0);
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
