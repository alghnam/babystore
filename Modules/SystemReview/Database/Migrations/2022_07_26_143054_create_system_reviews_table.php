<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_reviews', function (Blueprint $table) {
            $table->id();
                                                $table->string('locale')->default(config('app.locale'));

                 $table->unsignedBigInteger('system_review_type_id');
            $table->foreign('system_review_type_id')
                ->references('id')
                ->on('system_review_types')
                ->onDelete('cascade');
                
                
                            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
                $table->longText('body');
            $table->string('name');
            $table->string('email');
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
        Schema::dropIfExists('system_reviews');
    }
}
