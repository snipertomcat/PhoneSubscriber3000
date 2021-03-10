<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyBudget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_budget',function($table) {

            $table->increments('id');

            $table->integer('company_id')
                ->unsigned()
                ->index()
                ->nullable()
                ->default(null);

            $table->foreign('company_id')
                ->references('id')
                ->on('companies');

            $table->integer('company_areas_id')
                ->unsigned()
                ->index()
                ->nullable()
                ->default(null);

            $table->foreign('company_areas_id')
                ->references('id')
                ->on('company_areas');

            $table->integer('user_id')
                ->unsigned()
                ->index()
                ->nullable()
                ->default(null);

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->enum('type', ['global','annual', 'biannual','quarterly','monthly'])
                ->index()
                ->nullable()
                ->default('global');

            $table->decimal('init_balance')
                ->unsigned()
                ->index()
                ->nullable()
                ->default(0);

            $table->decimal('current_balance')
                ->unsigned()
                ->index()
                ->nullable()
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
        Schema::table('company_budget', function(Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['company_id']);
            $table->dropForeign(['company_areas_id']);
        });

        Schema::dropIfExists('company_budget');

    }
}
