<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlphaVantageKeysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alpha_vantage_keys')->insert([
            ['api_key' => "GVOHWUEZUV2UOA4E"],
            ['api_key' => "QBI5Z5I5CPAHWO2F"],
            ['api_key' => "KB9SB85LYEI1GYEI"],
            ['api_key' => "V9LVQIY83GA57LJC"],
            ['api_key' => "AZ48X0G3SL4OV0PV"],
            ['api_key' => "E8U3CHWSABESPLU6"],
        ]);
    }
}
