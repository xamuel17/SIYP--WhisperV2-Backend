<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            ['firstname' => 'John', 'lastname' => 'Ebri', 'email' => 'john.ebri@yahoo.com', 'password' => Hash::make('12345678'), 'web_role_id' => 1],
            ['firstname' => 'Samuel', 'lastname' => 'Unachukwu', 'email' => 'samuel@gmail.com', 'password' => Hash::make('12345678'), 'web_role_id' => 2],
            ['firstname' => 'Sammy', 'lastname' => 'Una', 'email' => 'sammy@gmail.com', 'password' => Hash::make('12345678'), 'web_role_id' => 3],
        ]);
    }
}
