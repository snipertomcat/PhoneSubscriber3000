<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExperienceAbilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('experience_ability', function (Blueprint $table) {

            $table->integer('experience_id')
                ->unsigned()
                ->index()
                ->default(null);

            $table->integer('ability_id')
                ->unsigned()
                ->index()
                ->default(null);

            $table->integer('company_id')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->foreign('experience_id')
                ->references('id')
                ->on('experiences')
                ;

            $table->foreign('ability_id')
                ->references('id')
                ->on('abilities')
                ;

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
            ;


            $table->integer('level')->index()
                ->default(100);

            $table->timestamps();

            $table->primary(['experience_id', 'ability_id']);

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
        Schema::dropIfExists('experience_ability');
    }
}
