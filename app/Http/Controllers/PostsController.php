<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Http\Requests\StorePost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PostsController extends Controller
{ 

    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mostCommentedBlogPosts = Cache::tags(['blog-post'])->remember('blog-post-most-commented', now()->addSeconds(30), function () {
            return BlogPost::mostCommented()->take(5)->get();
        });

        $mostActiveUsers = Cache::remember('users-most-active', now()->addSeconds(30), function () {
            return User::mostBlogPosts()->take(5)->get();
        });

        $mostActiveUserLastMonth = Cache::remember('users-most-active-last-month', now()->addSeconds(30), function () {
            return User::mostBlogPostsLastMonth()->take(5)->get();
        });

        $posts = BlogPost::latest()->with('user')->withCount('comments')->get();

        return view('posts.index', [
                'posts' => $posts,
                'mostCommented' => $mostCommentedBlogPosts,
                'mostActive' => $mostActiveUsers,
                'mostActiveLastMonth' => $mostActiveUserLastMonth,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        $validated = $request->validated();
        $validated = array_merge($validated, ['user_id' => $request->user()->id]);
        $blogPost = BlogPost::create($validated);

        $request->session()->flash('status', 'The blog post was created!');
        return redirect()->route('posts.show', ['post' => $blogPost->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // //one way to apply local scope to query (notice lamda after 'comments')
        // return view('posts.show', [
        //     'post' => BlogPost::with(['comments' => function ($query) {
        //         return $query->latest();
        //     }])->findOrFail($id),
        // ]);

        $sessionId = session()->getId();
        $counterKey = "blog-post-{$id}-counter";
        $usersKey = "blog-post-{$id}-users";

        $users = Cache::tags(['blog-post'])->get($usersKey, []);
        $usersUpdate = [];
        $difference = 0;
        $now = now();

        foreach($users as $session => $lastVisit) {
            if ($now->diffInMinutes($lastVisit) >= 1) {
                $difference--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        if (!array_key_exists($sessionId, $users) || 
                $now->diffInMinutes($users[$sessionId]) >= 1) {
            $difference++;
        }

        $usersUpdate[$sessionId] = $now;
        Cache::tags(['blog-post'])->forever($usersKey, $usersUpdate);

        if (!Cache::has($counterKey)) {
            Cache::tags(['blog-post'])->put($counterKey, 1);
        } else {
            Cache::tags(['blog-post'])->increment($counterKey, $difference);
        }

        $counter = Cache::tags(['blog-post'])->get($counterKey);

        $post = Cache::tags(['blog-post'])->remember("blog_post_{$id}",now()->addMinutes(30), function () use ($id) {
            return BlogPost::with('comments')->findOrFail($id);
        });

        //here we remove lamda and sorting of comments is done on relationship in the 
        //blog post model (see model)
        return view('posts.show', [
            'post' => $post,
            'counter' => $counter,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        $this->authorize($post);
        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        $this->authorize($post);
        $validated = $request->validated();
        $post->fill($validated);
        $post->save();

        $request->session()->flash('status', 'Blog post was updated.');
        return redirect()->route('posts.show', ['post' => $post->id]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        $this->authorize($post);
        $post->destroy($id);

        session()->flash('status', 'Blog post was deleted!');
        return redirect()->route('posts.index');
    }

    public function restore($id) {
        $post = BlogPost::withTrashed()->findOrFail($id);
        $post->restore();

        session()->flash('status', 'Blog post was restored!');
        return redirect()->route('posts.index');
    }
}
