<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Comment;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;
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
        $users = User::factory()->count(5)->create();
        $robert = User::factory()->createRobertKatz()->create();
        $users->concat([$robert]);
        
        $posts = BlogPost::factory()->count(50)->make()->each(function($post) use ($users) {
            $post->user_id = $users->random()->id;
            $post->save();
        });

        $comments = Comment::factory()->count(150)->make()->each(function($comment) use ($posts) {
            $comment->blog_post_id = $posts->random()->id;
            $comment->save();
        });

    }
}
