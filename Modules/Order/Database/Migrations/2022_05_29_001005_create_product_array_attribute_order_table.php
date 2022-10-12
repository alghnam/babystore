<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductArrayAttributeOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_array_attribute_order', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('product_array_attribute_id');
            $table->foreign('product_array_attribute_id')
                ->references('id')
                ->on('product_array_attributes')
                ->onDelete('cascade');
                
            $table->integer('quantity');
                        
                        
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
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
        Schema::dropIfExists('product_array_attribute_order');
    }
}
