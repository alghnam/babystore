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
            //  $table->unsignedBigInteger('product_attribute_id');
            // $table->foreign('product_attribute_id')
            //     ->references('id')
            //     ->on('attributes')
            //     ->onDelete('cascade'); 
            $table->longText('attributes');
                        $table->integer('quantity')->default(0);
			$table->float('original_price')->default(0);
			$table->float('price_discount_ends')->default(0);
			$table->string('sku')->nullable();
			$table->string('barcode')->nullable();
			$table->float('weight')->nullable();
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
