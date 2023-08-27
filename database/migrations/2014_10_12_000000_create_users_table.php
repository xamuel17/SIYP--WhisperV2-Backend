<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('username')->nullable();
            $table->string('password');
            $table->string('sex')->nullable();
            $table->string('dob')->nullable();
            $table->string('phone');
            $table->string('country')->nullable();
            $table->enum('language', ["en", "fr", "es", "sw" , "dn","none"])->default("none");
            $table->string('reg_location')->nullable();
            $table->string('email')->unique();
            $table->string('activation_code')->nullable();
            $table->timestamp('activation_time')->default(now()->addMinutes(10));
            $table->timestamp('email_verified_at')->nullable();
            $table->string('status')->nullable();
            $table->string('imei')->nullable();
            $table->string('profile_pic')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
