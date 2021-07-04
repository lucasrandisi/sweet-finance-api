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
            ['api_key' => 'GVOHWUEZUV2UOA4E'],
            ['api_key' => 'QBI5Z5I5CPAHWO2F'],
            ['api_key' => 'KB9SB85LYEI1GYEI'],
            ['api_key' => 'V9LVQIY83GA57LJC'],
            ['api_key' => 'AZ48X0G3SL4OV0PV'],
            ['api_key' => 'E8U3CHWSABESPLU6'],
            ['api_key' => 'FA2KKD42E5OQKDZX'],
            ['api_key' => 'ZI9XYU3ZAI9FRXP6'],
            ['api_key' => 'H8V5U7TSA0FDHK40'],
            ['api_key' => 'EM90LE85XVKQ87BB'],
            ['api_key' => '02TZP75RS82LIR31'],
            ['api_key' => '0CJITJT0QNM1T5CX'],
            ['api_key' => 'HJG0ZD3STP1XWZPA'],
            ['api_key' => 'UOW4ACF0R903KMFT'],
            ['api_key' => 'FECYXD2W32G3JQAS'],
            ['api_key' => 'VDMRPZ40342LD4GZ'],
            ['api_key' => '93BTD0DCEFINYQ6Q'],
            ['api_key' => 'CF4213XFAETN4G12'],
            ['api_key' => '7M2K8BDMCDP1CYXG'],
            ['api_key' => '2QAO6YRCNVZQYKNH'],
        ]);
    }
}
