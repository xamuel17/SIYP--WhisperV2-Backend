<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class UpdateGuardianDistressMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //


// Schema::table('guardian_distress_messages', function($table){
//     $table->longText('photo_base64')->nullable();
//     $table->longText('video_base64')->nullable();
//     $table->longText('audio_base64')->nullable();

// });


DB::statement('ALTER TABLE guardian_distress_messages MODIFY COLUMN photo TEXT');
DB::statement('ALTER TABLE guardian_distress_messages MODIFY COLUMN audio TEXT');
DB::statement('ALTER TABLE guardian_distress_messages MODIFY COLUMN video TEXT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE guardian_distress_messages MODIFY COLUMN photo VARCHAR(191)');
        DB::statement('ALTER TABLE guardian_distress_messages MODIFY COLUMN audio VARCHAR(191)');

        DB::statement('ALTER TABLE guardian_distress_messages MODIFY COLUMN video VARCHAR(191)');



        // Schema::table('guardian_distress_messages', function($table) {
        //     $table->dropColumn('photo_base64');
        //     $table->dropColumn('audio_base64');
        //     $table->dropColumn('video_base64');
        // });



        //
    }
}
