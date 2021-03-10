<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateUsersTable
 */
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('access')
                ->index()
                ->unique();

            $table->string('password');

            $table->string('registration_method', 40)
                ->nullable()
                ->index();

            $table->string('name')
                ->index()
                ->nullable();

            $table->string('surname')
                ->nullable();

            $table->string('email')
                ->index()
                ->nullable();

            $table->string('avatar')
                ->nullable();

            $table->date('birthday')
                ->nullable();

            $table->enum('gender', ['M', 'F'])
                ->index()
                ->default('M');

            $table->text('academic_history')
                ->nullable();

            $table->text('work_history')
                ->nullable();

            $table->boolean('banned')
                ->default(false);

            $table->boolean('activated')
                ->default(false);

            $table->rememberToken();

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
        Schema::dropIfExists('users');
    }
}
