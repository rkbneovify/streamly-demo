<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserZipcodeRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_zipcode_rules')->insert([
            [
                'email' => 'streamlymtairy@gmail.com',
                'zipcode' => '18344',
                'validate' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'email' => 'willis@cybrsecsolutions.com',
                'zipcode' => '75662',
                'validate' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'email' => 'streamlytestbox@gmail.com',
                'zipcode' => '85215',
                'validate' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
