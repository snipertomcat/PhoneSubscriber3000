<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExperienceLicences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiences_licences',function($table) {

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
                ->nullable()
                ->default(null);

            $table->foreign('user_id')
                ->references('id')
                ->on('users');


            $table->integer('status')
                ->unsigned()
                ->index()
                ->nullable();

            $table->integer('buyed_by')
                ->unsigned()
                ->index()
                ->nullable()
                ->default(null);

            $table->foreign('buyed_by')
                ->references('id')
                ->on('users');

            $table->integer('user_purchase_id')
                ->unsigned()
                ->index()
                ->nullable();

            $table->foreign('user_purchase_id')
                ->references('id')
                ->on('user_purchases')
                ->onUpdate('cascade');

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
        Schema::table('experiences_licences', function(Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['buyed_by']);
            $table->dropForeign(['experience_id']);
            $table->dropForeign(['user_purchases_id']);
        });

        Schema::dropIfExists('experiences_access');

    }
}
