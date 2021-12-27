<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketauxKeysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('marketaux_api_keys')->truncate();

		DB::table('marketaux_api_keys')->insert([
			['api_key' => 'cTqQ68JHcvtfH6KzK3q2CA1fecybNOi6OyjOJ2Vx'],
			['api_key' => 'OANwoYCOUil0pm43q3kZdiowlH2w7D0SWcJcE9nF'],
			['api_key' => 'dnp6k50BUDfeg5GObfoxcwTQsyBnyDoTau2tintt'],
			['api_key' => '122xYhCqkLgTCuQeZvRzt9l7deIpLrlCjHlNujT5'],
			['api_key' => 'KAap0v82xAR95dORMSyqEyz1RKTCJfvb7xpvbwPv'],
			['api_key' => '2cmvVqwKSowOKSbcjvHwOGQEhUaDr0fHOqWnwmWD'],
		]);
    }
}
