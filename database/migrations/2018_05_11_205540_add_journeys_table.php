<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJourneysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('journeys', function (Blueprint $table) {

            $table->increments('id');

            //
            $table->integer('journey_id')
                ->unsigned()
                ->index()
                ->default(null);

            $table->integer('experience_id')
                ->unsigned()
                ->index();

            $table->integer('parent_id')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->integer('weight')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(0);

            $table->foreign('journey_id')
                ->references('id')
                ->on('experiences');

            $table->foreign('experience_id')
                ->references('id')
                ->on('experiences');


            $table->foreign('parent_id')
                ->references('id')
                ->on('experiences')
                ;

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
        Schema::dropIfExists('journeys');
    }
}
