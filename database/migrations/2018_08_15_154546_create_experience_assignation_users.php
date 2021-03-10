<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExperienceAssignationUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experience_assignation_users', function(Blueprint $table) {
            $table->integer('assignation_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('experience_assignation_users',function($table) {
            $table->foreign('assignation_id')
                ->references('id')
                ->on('experience_assignations')
                ->onUpdate('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('experience_assignation_users', function ($table) {
            $table->dropForeign('experience_assignation_users_assignation_id_foreign');
            $table->dropForeign('experience_assignation_users_user_id_foreign');
        });

        Schema::dropIfExists('experience_assignation_users');
    }
}
