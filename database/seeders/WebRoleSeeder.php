<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('web_roles')->insert([
            ['name' => "APP_DEVELOPER", 'display_name' => 'App Developer', 'rank' => 100],
            ['name' => "SUPER_ADMIN", 'display_name' => 'Super Admin', 'rank' => 80],
            ['name' => "ADMIN", 'display_name' => 'Admin', 'rank' => 50],
        ]);
    }
}
