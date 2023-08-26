<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
          $table->id();
          $table->string('firstname', 50);
          $table->string('lastname', 50);
          $table->string('email')->unique();
          $table->timestamp('email_verified_at')->nullable();
          $table->string('password')->nullable();
          $table->rememberToken();
          $table->timestamps();
          $table->string('activation_code')->nullable();
          $table->timestamp('activation_code_sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
