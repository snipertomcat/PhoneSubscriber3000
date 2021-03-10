<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('country_id')->unsigned()->nullable()->index();
            $table->string('name')->index()->default('');
            $table->string('legal_name')->index()->default('');
            $table->string('short_name')->index()->default('');
            $table->string('domain')->index()->default('');
            $table->string('site_url')->index()->default('');
            $table->string('contact_email')->index()->default('');
            $table->string('contact_phone')->index()->default('');
            $table->string('logo')->index()->default('');
            $table->integer('status',false)->default(1);
            $table->timestamps();
            $table->unique(['name','legal_name']);

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('companies');
    }
}
