<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePrimaryKeyStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('stocks_transactions', function (Blueprint $table) {
            $table->dropForeign(['stock_id']);
        });

        Schema::table('stocks_users', function (Blueprint $table) {
            $table->dropForeign(['stock_id']);
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->string('id')->change();
            $table->renameColumn('id', 'symbol');
        });

        Schema::table('stocks_transactions', function (Blueprint $table) {
            $table->string('stock_id')->change();
            $table->renameColumn('stock_id', 'stock_symbol');

            $table->foreign('stock_symbol')->references('symbol')->on('stocks');
        });

        Schema::table('stocks_users', function (Blueprint $table) {
            $table->string('stock_id')->change();
            $table->renameColumn('stock_id', 'stock_symbol');

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
        Schema::table('stocks_transactions', function (Blueprint $table) {
            $table->dropForeign(['stock_symbol']);
        });

        Schema::table('stocks_users', function (Blueprint $table) {
            $table->dropForeign(['stock_symbol']);
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->id('symbol')->change();
            $table->renameColumn('symbol', 'id');
        });

        Schema::table('stocks_transactions', function (Blueprint $table) {
            $table->bigInteger('stock_symbol')->change();
            $table->renameColumn('stock_symbol', 'stock_id');

            $table->foreign('stock_id')->references('id')->on('stocks');
        });

        Schema::table('stocks_users', function (Blueprint $table) {
            $table->bigInteger('stock_symbol')->change();
            $table->renameColumn('stock_symbol', 'stock_id');

            $table->foreign('stock_id')->references('id')->on('stocks');
        });
    }
}
