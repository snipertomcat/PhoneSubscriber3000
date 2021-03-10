<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserPaymentInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('user_payment_information', function (Blueprint $table) {

            $table->increments('id');

            $table->string('payment_source_id')->nullable();

            $table->integer('user_id')
                ->unsigned()
                ->index();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->enum('provider', ['conekta', 'paypal', 'stripe', 'payu'])
                ->index()
                ->default(null);

            $table->tinyInteger('conekta_active')->default(0);

            $table->string('conekta_id')->nullable();

            $table->string('conekta_subscription')->nullable();

            $table->string('conekta_plan', 35)->nullable();

            $table->string('card_type', 30)->nullable();

            $table->string('last_four', 4)->nullable();

            $table->timestamp('trial_ends_at')->nullable();

            $table->timestamp('subscription_ends_at')->nullable();

            $table->integer('default_source')
                ->unsigned()
                ->index();

            $table->integer('status')
                ->unsigned()
                ->index()
                ->default(1);

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
        Schema::dropIfExists('user_payment_information');

    }
}
