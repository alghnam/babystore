<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductArrayAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_array_attributes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade'); 
                            $table->unsignedBigInteger('product_attribute_id');
            $table->foreign('product_attribute_id')
                ->references('id')
                ->on('attributes')
                ->onDelete('cascade'); 
            $table->integer('attributes');
                        $table->integer('quantity')->nullable();
			$table->integer('counter_discount')->nullable();
			$table->float('original_price')->nullable();
			$table->float('price_after_discount')->nullable();
			$table->float('price_discount_ends')->nullable();
			$table->string('sku');
			$table->string('barcode');
			$table->float('weight');
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
        Schema::dropIfExists('product_array_attributes');
    }
}
