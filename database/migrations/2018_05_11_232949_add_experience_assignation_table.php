<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExperienceAssignationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('experience_assignations', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('experience_id')
                ->unsigned()
                ->index()
                ->default(null);

            $table->foreign('experience_id')
                ->references('id')
                ->on('experiences');

            $table->integer('user_id')
                ->unsigned()
                ->index()
                ->default(null);

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->text('description')
                ->nullable()
                ->default(null);

            $table->enum('type', ['invitation', 'mandatory'])
                ->index()
                ->default('invitation');

            $table->json('company_visibility_settings')
                ->nullable()
                ->default(null);

            $table->json('user_visibility_settings')
                ->nullable()
                ->default(null);

            $table->integer('status')
                ->index()
                ->default(0);

            $table->date('start')
                ->index();

            $table->date('finish')
                ->index();

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
        Schema::dropIfExists('experience_assignations');

    }
}
