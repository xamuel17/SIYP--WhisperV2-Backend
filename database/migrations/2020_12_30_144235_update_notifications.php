<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        DB::statement('ALTER TABLE notifications MODIFY COLUMN content TEXT');


        Schema::table('notifications', function($table){
    $table->string('mobile_id')->nullable();


});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::statement('ALTER TABLE notifications MODIFY COLUMN content VARCHAR(191)');
    }
}
