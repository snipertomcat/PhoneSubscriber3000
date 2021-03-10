<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAbilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('abilities', function (Blueprint $table) {

            $table->increments('id');

            $table->string('title')->index();

            $table->string('description')->nullable();

            $table->integer('company_id')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
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
        Schema::dropIfExists('abilities');
    }
}
