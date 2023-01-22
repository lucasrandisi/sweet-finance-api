<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoriteStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite_stocks', function (Blueprint $table) {
			$table->id();

            $table->unsignedBigInteger('user_id');
			$table->string('stock_symbol');

			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('stock_symbol')->references('symbol')->on('stocks');

			$table->unique(['user_id', 'stock_symbol']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorite_stocks');
    }
}
