<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExperienceRate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiences_rating',function($table) {

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
                ->on('users');


            $table->enum('category', ['relevance','presentation' ,'satisfaction'])
                ->index()
                ->default('relevance');


            $table->integer('rating')
                ->unsigned()
                ->index()
                ->default(0);

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
        Schema::table('experiences_rating', function(Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['experience_id']);
        });

        Schema::dropIfExists('experiences_rating');

    }
}
