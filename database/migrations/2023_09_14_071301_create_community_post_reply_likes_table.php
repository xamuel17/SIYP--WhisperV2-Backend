<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunityPostReplyLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_post_reply_likes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['post', 'reply']);
            $table->bigInteger('selected_id');
            $table->bigInteger('user_id');
            $table->unsignedBigInteger('community_id');
            $table->boolean('action')->default(false);
            $table->timestamps();
            $table->foreign('community_id')->references('id')->on('communities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('community_post_reply_likes');
    }
}
