<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function($table) {
            $table->string('rfc')->after('legal_name')->nullable();
            $table->integer('zip')->after('status')->nullable();
            $table->integer('num_employees_min')->after('zip')->nullable();
            $table->integer('num_employees_max')->after('num_employees_min')->nullable();
            $table->string('company_province')->after('country_id')->nullable();
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
    }
}
