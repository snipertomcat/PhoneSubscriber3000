<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesHasCompanyAreas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /*
public function up()
{
    /*
:create('companies_company_areas', function (Blueprint $table) {

        $table->integer('company_id')->unsigned();

        $table->integer('company_area_id')->unsigned();
    });

    Schema::table('companies_company_areas', function ($table) {

        $table->foreign('company_id')
            ->references('id')
            ->on('companies')
            ->onUpdate('cascade')->onDelete('cascade');

        $table->foreign('company_area_id')
            ->references('id')
            ->on('company_areas')
            ->onUpdate('cascade')->onDelete('cascade');
    });
    }
*/

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    /*
public function down()
{
    /*
:table('companies_company_areas', function ($table) {
        $table->dropForeign('companies_company_areas_company_id_foreign');
        $table->dropForeign('companies_company_areas_company_area_id_foreign');
    });

    Schema::dropIfExists('companies_company_areas');
    }
*/
}
