<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TwelveDataKeysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	if (DB::table('twelve_data_keys')->count() == 0) {
			DB::table('twelve_data_keys')->insert([
				['api_key' => 'a8b44a06ac6240199ae65b77f62428f7'],
				['api_key' => '93d28f4a8a4f435bb0e2d1001008e5a5'],
				['api_key' => 'd0e7e140ffcd43e0b94721f42229cef5']
			]);
		}
    }
}
