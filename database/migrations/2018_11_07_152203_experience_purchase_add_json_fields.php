<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExperiencePurchaseAddJsonFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_purchases',function($table) {
            $table->integer('company_use')->nullable();
            $table->integer('personal_use')->nullable();
            $table->json('company_areas')->nullable();
            $table->json('company_positions')->nullable();
            $table->json('company_users')->nullable();
            $table->json('new_users')->nullable();
            $table->integer('empty_licences')->nullable();

            $table->integer('transaction_id')
                ->unsigned()
                ->index()
                ->nullable();

            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
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
        Schema::table('user_purchases', function(Blueprint $table) {
            $table->dropColumn(['company_use']);
            $table->dropColumn(['personal_use']);
            $table->dropColumn(['company_areas']);
            $table->dropColumn(['company_positions']);
            $table->dropColumn(['company_users']);
            $table->dropColumn(['new_users']);
            $table->dropColumn(['empty_licences']);
            $table->dropForeign(['transaction_id']);
        });
    }
}
