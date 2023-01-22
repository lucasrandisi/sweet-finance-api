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
		DB::table('twelve_data_keys')->truncate();

		DB::table('twelve_data_keys')->insert([
			['api_key' => 'a8b44a06ac6240199ae65b77f62428f7'],
			['api_key' => '93d28f4a8a4f435bb0e2d1001008e5a5'],
			['api_key' => 'd0e7e140ffcd43e0b94721f42229cef5'],
			['api_key' => '2a6e56c455b74ba69d9ab11f0caa7c91'],
			['api_key' => 'cc8f767d4478433ea531bf5ed5d0bf01'],
			['api_key' => 'c49160df19d146b29cbd25cdd6299b4b'],
		]);
    }
}
