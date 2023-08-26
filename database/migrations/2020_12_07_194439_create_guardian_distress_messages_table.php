<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuardianDistressMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guardian_distress_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('guardian_id');
            $table->string('ward_id');
               $table->string('title')->nullable();
            $table->text('content');
            $table->string('photo')->nullable();
            $table->string('video')->nullable();
            $table->string('audio')->nullable();
            $table->string('longitude');
            $table->string('latitude');
            $table->string('time_of_message');
            $table->string('phone_number')->nullable();
            $table->string('priority')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('guardian_distress_messages');
    }
}
