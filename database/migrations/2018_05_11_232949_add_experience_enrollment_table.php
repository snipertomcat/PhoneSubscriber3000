<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExperienceEnrollmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('experience_enrollment', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('experience_id')
                ->unsigned()
                ->index()
                ->default(null);

            $table->foreign('experience_id')
                ->references('id')
                ->on('experiences')
                ;

            $table->integer('user_id')
                ->unsigned()
                ->index()
                ->default(null);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ;

            $table->integer('experience_assignation_id')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->foreign('experience_assignation_id')
                ->references('id')
                ->on('experience_assignations');

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
        Schema::dropIfExists('experience_enrollment');

    }
}
