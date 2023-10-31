<?php

namespace Database\Seeders;

use App\Models\Rule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('rules')->insert([
            ['content' => 'Explicit content'],
            ['content' => 'Hate speech, harassment , bullying'],
            ['content' => 'Unverified or fake content'],
            ['content'=> 'Inappropriate content or behavior']
        ]);

    }
}
