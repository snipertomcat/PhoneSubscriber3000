<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateSchoolsTable
 */
class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('remote_id')
                ->index()
                ->nullable()
                ->default(null);

            $table->string('code', 40)
                ->default('');

            $table->string('name', 80)
                ->default('');

            $table->integer('country_id')
                ->unsigned()
                ->index();

            $table->integer('language_id')
                ->unsigned()
                ->index();

            $table->string('timezone', 12)
                ->default('');

            $table->timestamps();

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('cascade');

            $table->foreign('language_id')
                ->references('id')
                ->on('languages')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schools');
    }
}
