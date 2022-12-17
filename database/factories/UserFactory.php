<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    public function createRobertKatz()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Robert Katz',
                'email' => 'robertkatz1971@gmail.com',
                'password' => Hash::make('P@ssw0rd'),
                'is_admin' => 1,
            ];
        });
    }

    public function createAllaKatz()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Alla Katz',
                'email' => 'allakatz@gmail.com',
                'password' => Hash::make('P@ssw0rd'),
                'is_admin' => 0,
            ];
        });
    }
}
