<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentEnrollment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('experience_enrollment',function($table) {
            $table->integer('parent')->unsigned()
                ->index()
                ->nullable();

            $table->foreign('parent')
                ->references('id')
                ->on('experience_enrollment')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('experience_enrollment', function(Blueprint $table) {
            $table->dropForeign(['parent']);
            $table->dropColumn('parent');
        });
    }
}
