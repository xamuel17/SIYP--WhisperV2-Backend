<?php

namespace Database\Seeders;

use App\Models\Volunteer;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class VolunteerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        try {
            Volunteer::create([
                'user_id' => 2,
                'username' => "Dr. Olivia Thomas",
                'role' => "Therapist",
                'session'=> 0,
                'description'=> "Olivia is a highly experienced therapist who has dedicated her life to helping others. With years of experience under her belt, she has developed a deep understanding of the complexities of mental health and the different ways in which people cope with life's challenges. Olivia is passionate about empowering her clients to take control of their mental health, and helping them find a path towards greater happiness and fulfillment. Her kind and compassionate approach, combined with her extensive knowledge and expertise, make her an invaluable resource for anyone seeking support and guidance on their mental health journey.",
                'email' => "oliviathomas@gmail.com",
                'phone' =>"+2349029088929"
            ]);



            Volunteer::create([
                'user_id' => 3,
                'username' => "Dr. James Bones",
                'role' => "Therapist",
                'session'=> 0,
                'description'=> "James is a highly experienced therapist who has dedicated her life to helping others. With years of experience under her belt, she has developed a deep understanding of the complexities of mental health and the different ways in which people cope with life's challenges. Olivia is passionate about empowering her clients to take control of their mental health, and helping them find a path towards greater happiness and fulfillment. Her kind and compassionate approach, combined with her extensive knowledge and expertise, make her an invaluable resource for anyone seeking support and guidance on their mental health journey.",
                'email' => "Jamesbones@gmail.com",
                'phone' =>"+2349029088929"
            ]);


        } catch (\Throwable $th) {
            //throw $th;
        }





    }
}
