<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {



        return [
            'firstname' => $this->faker->name,
            'username' => $this->faker->name,
            'lastname' => $this->faker->name,
            'phone'=>$this->faker->text(11),
            'sex'=>'male',
            'dob'=>'13-8-1983',
            'status'=>'active',
            'profile_pic'=>'none',
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'country' => $this->faker->text(10),
        ];
    }
}
