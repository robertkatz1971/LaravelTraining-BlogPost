<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Comment;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use SebastianBergmann\Environment\Console;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if ($this->command->confirm('Do you want to refresh the database?')) {
            $this->command->call('migrate:refresh');
            $this->command->info('Database refreshed');
        }

        Cache::tags('blog_post')->flush();

        $this->call([
            UsersTableSeeder::class,
            BlogPostTableSeeder::class,
            CommentsTableSeeder::class,
        ]);
    }
}
