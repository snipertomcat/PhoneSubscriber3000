<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Class CharifyCountriesTable
 */
class CharifyCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return  void
	 */
	public function up()
	{
        $name = config('countries.table_name');

        Schema::table(config('countries.table_name'), function() use ($name) {
            DB::statement("ALTER TABLE " . DB::getTablePrefix() . $name . " MODIFY country_code CHAR(3) NOT NULL DEFAULT ''");
            DB::statement("ALTER TABLE " . DB::getTablePrefix() . $name . " MODIFY iso_3166_2 CHAR(2) NOT NULL DEFAULT ''");
            DB::statement("ALTER TABLE " . DB::getTablePrefix() . $name . " MODIFY iso_3166_3 CHAR(3) NOT NULL DEFAULT ''");
            DB::statement("ALTER TABLE " . DB::getTablePrefix() . $name . " MODIFY region_code CHAR(3) NOT NULL DEFAULT ''");
            DB::statement("ALTER TABLE " . DB::getTablePrefix() . $name . " MODIFY sub_region_code CHAR(3) NOT NULL DEFAULT ''");
        });
    }
	

	/**
	 * Reverse the migrations.
	 *
	 * @return  void
	 */
	public function down()
	{
        $name = config('countries.table_name');

        Schema::table(config('countries.table_name'), function() use ($name) {
            DB::statement("ALTER TABLE " . DB::getTablePrefix() . $name . " MODIFY country_code VARCHAR(3) NOT NULL DEFAULT ''");
            DB::statement("ALTER TABLE " . DB::getTablePrefix() . $name . " MODIFY iso_3166_2 VARCHAR(2) NOT NULL DEFAULT ''");
            DB::statement("ALTER TABLE " . DB::getTablePrefix() . $name . " MODIFY iso_3166_3 VARCHAR(3) NOT NULL DEFAULT ''");
            DB::statement("ALTER TABLE " . DB::getTablePrefix() . $name . " MODIFY region_code VARCHAR(3) NOT NULL DEFAULT ''");
            DB::statement("ALTER TABLE " . DB::getTablePrefix() . $name . " MODIFY sub_region_code VARCHAR(3) NOT NULL DEFAULT ''");
        });
	}

}
