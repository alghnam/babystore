<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeoImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('seo_id')->nullable();
            $table->foreign('seo_id')
                ->references('id')
                ->on('seos')
                ->onDelete('cascade');
            $table->string('filename');

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
        Schema::dropIfExists('seo_images');
    }
}
