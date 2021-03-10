<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddSchoolIdFieldOnUsersTable
 */
class AddSchoolIdFieldOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
            $table->integer('school_id')
                ->after('id')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->foreign('school_id')
                ->references('id')
                ->on('schools')
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
        Schema::table('users', function($table) {
            $table->dropForeign('users_school_id_foreign');
            $table->dropColumn('school_id');
        });
    }
}
