<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class MobilePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('mobile_pages')->insert([
            ['page_name' => 'Take Photo', 'router_link' => '/tabs/tab2/take-a-picture'],
            ['page_name' => 'User Profile', 'router_link' => '/user-profile'],
            ['page_name' => 'Pending Request', 'router_link' => '/tabs/tab2/pennging'],
            ['page_name' => 'Broadcast', 'router_link' => '/tabs/tab2/broadcast'],
            ['page_name' => 'Distress-message', 'router_link' => '/tabs/tab2/distress'],

        ]);


    }
}
