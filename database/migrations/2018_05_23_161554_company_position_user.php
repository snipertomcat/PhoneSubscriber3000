<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyPositionUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_position_user', function (Blueprint $table) {
            $table->integer('company_position_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('company_position_id')
                ->references('id')
                ->on('company_positions')
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
        Schema::table('company_position_user', function ($table) {
            $table->dropForeign('company_position_user_company_position_id_foreign');
            $table->dropForeign('company_position_user_user_id_foreign');
        });

        Schema::dropIfExists('company_position_user');
    }
}
