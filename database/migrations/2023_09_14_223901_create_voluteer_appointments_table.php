<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoluteerAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voluteer_appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('volunteer_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('appointment_type', ['chat', 'call','email']);
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->longText('notes');
            $table->enum('status', ['cancelled', 'closed','completed','pending', 'accepted'])->default('pending');
            $table->timestamps();
            $table->foreign('volunteer_id')->references('id')->on('volunteers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voluteer_appointments');
    }
}
