<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OperationType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_budget', function (Blueprint $table) {
            $table->enum('operation_type',['in','out'])->default('in');
        });

        DB::statement('alter table company_budget change init_balance amount decimal(8,2) unsigned default 0.00 null');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_budget', function(Blueprint $table) {
            $table->removeColumn('contact_preference');
            $table->removeColumn('operation_type');
        });
    }
}
