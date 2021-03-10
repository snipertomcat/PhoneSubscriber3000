<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateLanguagesTable
 */
class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('remote_id')
                ->index()
                ->nullable()
                ->default(null);

            $table->string('code', 20)
                ->default('');

            $table->string('name', 60)
                ->default('');

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
        Schema::dropIfExists('languages');
    }
}
