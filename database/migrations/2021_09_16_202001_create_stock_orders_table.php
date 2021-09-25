<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_orders', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('user_id');
			$table->string('stock_symbol');
			$table->enum('action', ['BUY', 'SELL']);
			$table->integer('amount');
			$table->float('limit');
			$table->float('stop')->nullable();
			$table->enum('state', ['INACTIVE', 'ACTIVE', 'SOLD']);
			$table->softDeletes();

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
        Schema::dropIfExists('stock_orders');
    }
}
