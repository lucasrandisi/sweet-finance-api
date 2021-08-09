<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks_users', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->string('stock_symbol');
            $table->integer('amount');


            $table->primary(['user_id', 'stock_symbol']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('stock_symbol')->references('symbol')->on('stocks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks_users');
    }
}
