<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyAreas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned()->default(0);
            $table->string('name')->index()->default('');
            $table->string('short_name')->default('');
            $table->string('description')->default(null)->nullable();
            $table->nestedSet();
            $table->timestamps();
        });

        /*Schema::table('company_areas', function (Blueprint $table) {

            $table->foreign('company_id')
                ->references('id')
                ->on('companies');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*
        Schema::table('companies_company_areas', function ($table) {
            $table->dropForeign('company_areas_company_id_foreign');
        });
        */

        Schema::dropIfExists('company_areas');
    }
}
