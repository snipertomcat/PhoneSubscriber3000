<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('experience_sessions', function (Blueprint $table) {

            $table->increments('id');

            $table->enum('type', ['exploration', 'practice', 'exam'])
                ->index()
                ->default('exploration');

            $table->string('title')->index()->nullable()
                ->default(null);

            $table->text('summary')->nullable()
                ->default(null);

            $table->text('description')->nullable()->default(null);

            $table->text('resource_url')->nullable()
                ->default(null);

            $table->integer('visibility')->index()
                ->nullable()->default(null);

            $table->json('company_visibility_settings')
                ->nullable()->default(null);

            $table->json('user_visibility_settings')
                ->nullable()->default(null);


            $table->integer('status')->index()
                ->default(2);

            $table->integer('weight')->index()
                ->default(0);

            $table->date('available_from')->index()->nullable()->default(null);

            $table->date('available_to')->index()->nullable()->default(null);

            $table->string('cover')->nullable()->default(null);

            $table->integer('author')
                ->unsigned()
                ->index()
                ->default(null);


            $table->foreign('author')
                ->references('id')
                ->on('users');

            $table->integer('partner')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->foreign('partner')
                ->references('id')
                ->on('users');

            $table->integer('experience_id')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->foreign('experience_id')
                ->references('id')
                ->on('experiences');

            $table->integer('company_id')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->foreign('company_id')
                ->references('id')
                ->on('companies');

            $table->nestedSet();

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
        Schema::dropIfExists('experience_sessions');
    }
}
