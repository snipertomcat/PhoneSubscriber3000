<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExperienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('experiences', function (Blueprint $table) {

            $table->increments('id');

            $table->enum('type', ['journey', 'adventure'])
                ->index()
                ->default('adventure');

            $table->string('title')->index()->nullable()
                ->default(null);

            $table->text('summary')->nullable()
                ->default(null);

            $table->text('description')->nullable()->default(null);

            $table->string('code')->index()->nullable()->default(null);

            $table->text('company_objectives')->nullable()->default(null);

            $table->text('area_objectives')->nullable()->default(null);

            $table->integer('points_default')
                ->nullable()->default(null);

            $table->integer('duration_limit_default')
                ->nullable()->default(null);

            $table->integer('level_default')
                ->nullable()->default(null);

            $table->float('price_default')->nullable()
                ->nullable()->default(null);

            $table->integer('visibility')->index()
                ->nullable()->default(null);

            $table->json('company_visibility_settings')
                ->nullable()->default(null);

            $table->json('user_visibility_settings')
                ->nullable()->default(null);

            $table->date('available_from')->index()->nullable()->default(null);

            $table->date('available_to')->index()->nullable()->default(null);

            $table->string('cover')->nullable()->default(null);

            $table->string('video_intro')->nullable()->default(null);

            $table->integer('author')
                ->unsigned()
                ->index()
                ->default(null);


            $table->foreign('author')
                ->references('id')
                ->on('users')
                ;

            $table->integer('partner')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->foreign('partner')
                ->references('id')
                ->on('users')
                ;

            $table->integer('company_id')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->foreign('company_id')
                ->references('id')
                ->on('companies');

            $table->integer('status')->index()
                ->default(2);

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
        Schema::table('experiences', function ($table) {
            $table->dropForeign('experiences_company_id_foreign');
            $table->dropForeign('experiences_partner_foreign');
            $table->dropForeign('experiences_author_foreign');

        });

        //
        Schema::dropIfExists('experiences');
    }
}
