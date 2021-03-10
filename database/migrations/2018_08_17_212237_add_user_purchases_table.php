<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_purchases', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('user_id')
                ->unsigned()
                ->index();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->integer('item_id')
                ->unsigned()
                ->index()
                ->default(null);

            $table->enum('item_type', ['experience'])
                ->index()
                ->default('experience');
            

            $table->integer('assignation_id')
                ->unsigned()
                ->index()
                ->nullable();

            $table->foreign('assignation_id')
                ->references('id')
                ->on('experience_assignations')
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
        //
        Schema::dropIfExists('experience_enrollment_tracking');

    }
}
