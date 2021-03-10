<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddRoleIdFieldOnInvitationsTable
 */
class AddRoleIdFieldOnInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invitations', function($table) {
            $table->integer('role_id')
                ->after('id')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
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
        Schema::table('invitations', function($table) {
            $table->dropForeign('invitations_role_id_foreign');
            $table->dropColumn('role_id');
        });
    }
}
