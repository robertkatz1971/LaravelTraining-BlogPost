<?php

namespace App\Http\ViewComposers;

use App\Models\User;
use App\Models\BlogPost;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class ActivityComposer {

    public function compose(View $view) {
        
        $mostCommentedBlogPosts = Cache::tags(['blog-post'])->remember('blog-post-most-commented', now()->addMinutes(30), function () {
            return BlogPost::mostCommented()->take(5)->get();
        });

        $mostActiveUsers = Cache::remember('users-most-active', now()->addMinutes(30), function () {
            return User::mostBlogPosts()->take(5)->get();
        });

        $mostActiveUserLastMonth = Cache::remember('users-most-active-last-month', now()->addMinutes(30), function () {
            return User::mostBlogPostsLastMonth()->take(5)->get();
        });

        $view->with('mostCommented', $mostCommentedBlogPosts);
        $view->with('mostActive', $mostActiveUsers);
        $view->with('mostActiveLastMonth', $mostActiveUserLastMonth);
    }
}