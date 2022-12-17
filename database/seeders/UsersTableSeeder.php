<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userCount = max((int)$this->command->ask('How many users would you like?', 5),1);
        User::factory()->count($userCount - 2)->create();

        $robert = User::where('email', '=', 'robertkatz1971@gmail.com')->first();
        if (!$robert) {
            User::factory()->createRobertKatz()->create();
        }
        $alla = User::where('email', '=', 'allakatz@gmail.com')->first();
        if (!$alla) {
            User::factory()->createAllaKatz()->create();
        }
    }
}
