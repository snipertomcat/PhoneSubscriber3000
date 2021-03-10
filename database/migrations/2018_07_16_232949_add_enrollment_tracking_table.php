<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnrollmentTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('experience_enrollment_tracking', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('enrollment_progress_id')
                ->unsigned()
                ->index()
                ->default(null);

            $table->foreign('enrollment_progress_id')
                ->references('id')
                ->on('experience_enrollment_progress')
            ;

            $table->timestamp('access_time')
                ->nullable()
                ->index()
                ->default(null);

            $table->string('event_type')
                ->nullable()
                ->index()
                ->default(null);


            $table->string('verb')
                ->nullable()
                ->index()
                ->default(null);

            $table->string('object')
                ->nullable()
                ->index()
                ->default(null);

            $table->string('duration')
                ->nullable()
                ->index()
                ->default(null);


            $table->integer('min_score')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->integer('max_score')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->integer('raw_score')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->float('scaled_score')
                ->nullable()
                ->index()
                ->default(null);

            $table->json('results_json')
                ->nullable()
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
        Schema::dropIfExists('experience_enrollment_tracking');

    }
}
