<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoluteerAvailableDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voluteer_available_days', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('volunteer_id');
            $table->enum('day_of_week', ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
            $table->foreign('volunteer_id')->references('id')->on('volunteers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voluteer_available_days');
    }
}
