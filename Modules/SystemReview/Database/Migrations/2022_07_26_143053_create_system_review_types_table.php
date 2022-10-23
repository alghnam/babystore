<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemReviewTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_review_types', function (Blueprint $table) {
            $table->id();
                                                $table->string('locale')->default(config('app.locale'));

            $table->string('name');
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
        Schema::dropIfExists('system_review_types');
    }
}
