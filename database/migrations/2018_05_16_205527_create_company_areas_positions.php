<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyAreasPositions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /*
    public function up()
    {
        Schema::create('company_areas_positions', function (Blueprint $table) {
            $table->integer('company_area_id')->unsigned();

            $table->integer('company_position_id')->unsigned();

            $table->string('area')->default('');
        });

        Schema::table('company_areas_positions', function ($table) {

            $table->foreign('company_area_id')
                ->references('id')
                ->on('company_areas')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('company_position_id')
                ->references('id')
                ->on('company_positions')
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
        Schema::table('company_areas_positions', function ($table) {
            $table->dropForeign('company_areas_positions_company_area_id_foreign');
            $table->dropForeign('company_areas_positions_company_position_id_foreign');
        });

        Schema::dropIfExists('company_areas_positions');
    }
    */
}
