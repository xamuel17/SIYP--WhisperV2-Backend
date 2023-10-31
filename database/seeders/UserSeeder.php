<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([

            ['firstname' => 'Samuel', 'lastname' => 'unachukwu', 'username' => 'sammy','password' =>  Hash::make('password123'),'sex' => 'male','dob' => '12-09-1999','country' => 'Nigeria','phone' => '+2349025750231','email' => 'unachukwu.samuel@gmail.com','status'=>'active'],
            ['firstname' => 'Roland', 'lastname' => 'shile', 'username' => 'roland','password' =>  Hash::make('password123'),'sex' => 'male','dob' => '12-09-1999','country' => 'Nigeria','phone' => '+2347062544741','email' => 'shileroland@gmail.com','status'=>'active'],
        ]
    );
    }
}
