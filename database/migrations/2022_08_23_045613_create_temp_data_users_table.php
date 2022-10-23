<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempDataUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_data_users', function (Blueprint $table) {
            $table->id();
                        $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('amount')->nullable();
            $table->unsignedBigInteger('payment_id');
            $table->foreign('payment_id')
                ->references('id')
                ->on('payments')
                ->onDelete('cascade')->nullable();
                            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade')->nullable();
            
            $table->integer('phone_no_address')->nullable();
            $table->integer('payment_status_id')->nullable();
            $table->string('coupon_name')->nullable();
            $table->string('coupon_value')->nullable();
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
        Schema::dropIfExists('temp_data_users');
    }
}
