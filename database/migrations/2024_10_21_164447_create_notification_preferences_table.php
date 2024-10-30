<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->enum('alert_control', ['Y', 'N'])->default('N');
            $table->enum('geo_fencing', ['Y', 'N'])->default('N');
            $table->enum('audio_recording', ['Y', 'N'])->default('N');
            $table->enum('panic_alert', ['Y', 'N'])->default('N');
            $table->enum('show_notification', ['Y', 'N'])->default('N');
            $table->enum('timer', ['Y', 'N'])->default('N');
            $table->enum('theme', ['light', 'dark'])->default('light');
            $table->timestamps();
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
        Schema::dropIfExists('notification_preferences');
    }
}
