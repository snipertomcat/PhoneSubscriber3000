<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBudgets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->decimal('budget_balance',11,2)->unsigned()->change();
        });

        /*
        Schema::table('company_budget', function (Blueprint $table) {
            $table->decimal('current_balance',11,2)->change();
            $table->decimal('amount',11,2)->change();
        });
        */

        DB::statement('ALTER TABLE `company_budget` MODIFY `amount` DECIMAL(11,2) UNSIGNED');
        DB::statement('ALTER TABLE `company_budget` MODIFY `current_balance` DECIMAL(11,2) UNSIGNED');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
