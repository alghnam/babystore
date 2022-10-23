<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('key_rand');
            $table->string('amount');
            $table->unsignedBigInteger('payment_id');
            $table->foreign('payment_id')
                ->references('id')
                ->on('payments')
                ->onDelete('cascade');
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
        Schema::dropIfExists('temp_transactions');
    }
}
