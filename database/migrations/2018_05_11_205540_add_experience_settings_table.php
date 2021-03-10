<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExperienceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('experience_company', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('experience_id')
                ->unsigned()
                ->index()
                ->default(null);

            $table->integer('company_id')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->foreign('company_id')
                ->references('id')
                ->on('companies');

            $table->integer('instructor')
                ->unsigned()
                ->nullable()
                ->default(null);

            $table->text('company_objectives')->nullable()->default(null);

            $table->text('area_objectives')->nullable()->default(null);

            $table->integer('points')
                ->nullable()->default(null);

            $table->integer('duration_limit')
                ->nullable()->default(null);

            $table->integer('level')
                ->nullable()->default(null);

            $table->float('price')->nullable()
                ->nullable()->default(null);

            $table->integer('visibility')->index()
                ->nullable()->default(null);

            $table->json('company_visibility_settings')
                ->nullable()->default(null);

            $table->json('user_visibility_settings')
                ->nullable()->default(null);

            $table->date('available_from')->index()->nullable()->default(null);

            $table->date('available_to')->index()->nullable()->default(null);

            $table->date('start')->nullable()->index();

            $table->date('finish')->nullable()->index();

            $table->integer('status')->index()
                ->default(0);

            $table->string('cover')->nullable()->default(null);

            $table->foreign('experience_id')
                ->references('id')
                ->on('experiences')
                ;

            $table->foreign('instructor')
                ->references('id')
                ->on('users')
                ;

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
        //
        Schema::dropIfExists('experience_company');

    }
}
