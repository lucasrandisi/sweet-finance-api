<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('stock_symbol');
            $table->integer('amount');
            $table->enum('action', ['BUY', 'SELL']);
            $table->float('price');
            $table->timestampsTz();

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
        Schema::dropIfExists('stocks_transactions');
    }
}
