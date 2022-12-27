<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Comment;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        $posts = BlogPost::all();
        $users = User::all();

        if($posts->count() === 0) {
            $this->command->info('No comments were created since there are no Blog Posts');
            return;
        }

        $commentsCount = (int)$this->command->ask('How many Comments would you like?', 150);
        Comment::factory()->count($commentsCount)->make()->each(function($comment) use ($posts, $users) {
            $comment->blog_post_id = $posts->random()->id;
            $comment->user_id = $users->random()->id;
            $comment->save();
        });
    }
}
