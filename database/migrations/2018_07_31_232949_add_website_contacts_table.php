<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWebsiteContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('website_contacts', function (Blueprint $table) {

            $table->increments('id');

            $table->string('name')
                ->index()
                ->default(null);

            $table->string('email')
                ->index()
                ->default(null);

            $table->string('company')
                ->nullable()
                ->index()
                ->default(null);


            $table->string('website')
                ->nullable()
                ->index()
                ->default(null);

            $table->string('phone')
                ->nullable()
                ->index()
                ->default(null);

            $table->integer('country_id')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->integer('status')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->enum('type', ['website-register', 'partner'])
                ->index()
                ->default(null);

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('set null');


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
        Schema::dropIfExists('website_contacts');

    }
}
