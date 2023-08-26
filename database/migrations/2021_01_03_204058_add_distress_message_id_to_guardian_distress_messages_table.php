<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistressMessageIdToGuardianDistressMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guardian_distress_messages', function (Blueprint $table) {
            $table->integer('distress_message_id')->after('id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guardian_distress_messages', function (Blueprint $table) {
            $table->dropColumn('distress_message_id');
        });
    }
}
