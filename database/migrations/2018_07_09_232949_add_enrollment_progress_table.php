<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnrollmentProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('experience_enrollment_progress', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('enrollment_id')
                ->unsigned()
                ->index()
                ->default(null);

            $table->foreign('enrollment_id')
                ->references('id')
                ->on('experience_enrollment')
                ;

            $table->integer('session_id')
                ->unsigned()
                ->index()
                ->default(null);

            $table->foreign('session_id')
                ->references('id')
                ->on('experience_sessions')
                ;

            $table->integer('status')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

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
        //
        Schema::dropIfExists('experience_enrollment_progress');

    }
}
