<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhoneAndCompanyFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('phone')
                ->after('work_history')
                ->nullable()
                ->index()
                ->default(null);
        });

        Schema::table('company_user', function ($table) {
            $table->string('job_area')
                ->nullable()
                ->index()
                ->default(null);

            $table->string('job_title')
                ->nullable()
                ->index()
                ->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('phone');
        });

        Schema::table('company_user', function ($table) {
            $table->dropColumn('job_title');
            $table->dropColumn('job_area');
        });
    }
}
