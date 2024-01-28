<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->bigIncrements("_id");
            $table->unsignedBigInteger("volunteer_id")->nullable();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->uuid('chat_id')->nullable();
            $table->longText("text")->nullable();
            $table->string("image")->nullable();
            $table->boolean("sent")->default(false);
            $table->boolean("received")->default(false);
            $table->string("started")->nullable();
            $table->timestamps();
            $table->foreign('volunteer_id')->references('user_id')->on('volunteers')->onDelete('cascade');
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
        Schema::dropIfExists('chats');
    }
}
