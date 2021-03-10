<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExperienceAddInstructorsTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::table('user_purchases',function($table) {
            $table->json('company_area')->nullable();
            $table->json('features')->nullable();
        });
        */
        Schema::table('experiences',function($table) {
            $table->json('instructors')->nullable();
            $table->json('features')->nullable();
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
    }
}
