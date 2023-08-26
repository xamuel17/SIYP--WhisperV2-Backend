<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class UpdateDistressMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //DB::query("ALTER TABLE `ltest`.`users` CHANGE COLUMN `active` `active` tinyint(1) NOT NULL DEFAULT '1';");

// Schema::table('distress_messages', function($table){
//     $table->string('photo_base64')->nullable();
//     $table->string('video_base64')->nullable();
//     $table->string('audio_base64')->nullable();

// });

DB::statement('ALTER TABLE distress_messages MODIFY COLUMN photo TEXT');
DB::statement('ALTER TABLE distress_messages MODIFY COLUMN audio TEXT');
DB::statement('ALTER TABLE distress_messages MODIFY COLUMN video TEXT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //

        DB::statement('ALTER TABLE distress_messages MODIFY COLUMN photo VARCHAR(191)');
        DB::statement('ALTER TABLE distress_messages MODIFY COLUMN audio VARCHAR(191)');

        DB::statement('ALTER TABLE distress_messages MODIFY COLUMN video VARCHAR(191)');



        // Schema::table('distress_messages', function($table) {
        //     $table->dropColumn('photo_base64');
        //     $table->dropColumn('audio_base64');
        //     $table->dropColumn('video_base64');
        // });
    }
}
