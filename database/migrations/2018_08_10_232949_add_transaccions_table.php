<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransaccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('transactions', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('user_payment_information_id')
                ->unsigned()
                ->index();

            $table->foreign('user_payment_information_id')
                ->references('id')
                ->on('user_payment_information')
                ->onDelete('cascade');

            $table->integer('user_id')
                ->unsigned()
                ->index();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->enum('type', ['charge', 'refund', 'adjustment','payout'])
                ->index()
                ->default(null);

            $table->float('amount')->nullable();

            $table->string('provider_charge_id', 30)->nullable();

            $table->string('provider_refund_id', 30)->nullable();

            $table->string('provider_customer_id', 30)->nullable();

            $table->string('provider_payout_id', 30)->nullable();
            
            $table->string('provider_payment_source_id', 30)->nullable();

            $table->string('charge_code', 30)->nullable();

            $table->text('description')->nullable();

            $table->integer('status')
                ->unsigned()
                ->index();

            $table->timestamps();

        });

        Schema::create('transaction_details', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('transaction_id')
                ->unsigned()
                ->index();

            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->onDelete('cascade');


            $table->float('price')->nullable();


            $table->integer('assignation_id')
                ->unsigned()
                ->nullable()
                ->index();


            $table->foreign('assignation_id')
                ->references('id')
                ->on('experience_assignations');


            $table->integer('experience_id')
                ->unsigned()
                ->nullable()
                ->index();

            $table->foreign('experience_id')
                ->references('id')
                ->on('experiences');


            $table->text('description')->nullable();


            $table->integer('status')
                ->unsigned()
                ->default(0)
                ->index();

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
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('transactions');

    }
}
