<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->unsignedBigInteger('town_id');
            $table->foreign('town_id')->references('id')->on('towns')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->float('latitude');
            $table->float('longitute');

            $table->string('home_no');
            $table->string('phone_no');
            $table->string('piece_number');
            $table->string('street_number')->nullable();
            $table->string('jada_number')->nullable();
            $table->string('floor_number')->nullable();
            $table->string('apartment_number')->nullable();
            $table->string('additional_tips')->nullable();
            $table->tinyInteger('default_address')->default(0);
            $table->tinyInteger('confirmed')->default(0);


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
        Schema::dropIfExists('addresses');
    }
}
