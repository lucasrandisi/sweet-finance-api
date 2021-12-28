<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FmpApiKeysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('fmp_api_keys')->truncate();

		DB::table('fmp_api_keys')->insert([
			['api_key' => '7642d3f9915a93b064b41ec5f86f8dde'],
			['api_key' => '4c611e15fa485cf77d5747fbb9b01ff4'],
			['api_key' => 'a0fcc84109590b78f36b9447a025625d'],
			['api_key' => 'f835357c2c822ce459614d933f725c4b'],
			['api_key' => '8587f7a5261207e8ed9a3a01c2439d99'],
			['api_key' => '261400d1fbacbeb04eaa08b863ac1ddc'],
		]);
    }
}
