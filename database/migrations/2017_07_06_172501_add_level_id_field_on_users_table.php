<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddLevelIdFieldOnUsersTable
 */
class AddLevelIdFieldOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
            $table->integer('level_id')
                ->after('school_id')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->foreign('level_id')
                ->references('id')
                ->on('levels')
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
            $table->dropForeign('users_level_id_foreign');
            $table->dropColumn('level_id');
        });
    }
}
