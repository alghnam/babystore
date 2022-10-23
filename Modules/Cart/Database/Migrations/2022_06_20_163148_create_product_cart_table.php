<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_cart', function (Blueprint $table) {
            $table->id();
                        $table->unsignedBigInteger('product_array_attribute_id')->nullable();
            $table->foreign('product_array_attribute_id')
                ->references('id')
                ->on('product_array_attributes')
                ->onDelete('cascade');
                            $table->unsignedBigInteger('cart_id')->nullable();
            $table->foreign('cart_id')
                ->references('id')
                ->on('carts')
                ->onDelete('cascade');
            $table->integer('quantity')->default(0);
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
        Schema::dropIfExists('product_cart');
    }
}
