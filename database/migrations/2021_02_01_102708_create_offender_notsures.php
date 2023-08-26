<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffenderNotsures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offender_notsures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('offence_id');
            $table->string('user_id');
            $table->timestamps();

            $table->foreign('offence_id')->references('id')->on('offenders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offender_notsures');
    }
}
