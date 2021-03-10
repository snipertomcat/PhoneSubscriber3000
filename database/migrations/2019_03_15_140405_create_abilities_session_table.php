<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbilitiesSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abilities_session', function (Blueprint $table) {
            $table->integer('session_id')->unsigned();
            $table->integer('ability_id')->unsigned();
            $table->timestamps();

            $table->foreign('ability_id')->references('id')->on('abilities')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('experience_sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('abilities_session', function (Blueprint $table) {
            $table->dropForeign(['session_id','ability_id']);
        });

        Schema::dropIfExists('abilities_session');
    }
}
