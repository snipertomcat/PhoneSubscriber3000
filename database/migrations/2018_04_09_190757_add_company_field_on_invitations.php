<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyFieldOnInvitations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invitations', function ($table) {
            $table->integer('company_id')
                ->after('id')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->integer('user_id')
                ->after('role_id')
                ->unsigned()
                ->nullable()
                ->index()
                ->default(null);

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('set null');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->dateTime('last_send')
                ->nullable();

            $table->integer('status')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invitations', function ($table) {
            $table->dropForeign('invitations_company_id_foreign');
            $table->dropForeign('invitations_user_id_foreign');

            $table->dropColumn('user_id');
            $table->dropColumn('company_id');
        });
    }
}
