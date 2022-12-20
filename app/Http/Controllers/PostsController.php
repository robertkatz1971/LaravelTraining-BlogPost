<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Http\Requests\StorePost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{ 

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // DB::enableQueryLog();

        // $posts = BlogPost::with('comments')->get();

        // foreach($posts as $post) {
        //     foreach ($post->comments as $comment) {
        //         echo $comment->content;
        //     }
        // }

        // dd(DB::getQueryLog());

        // $post = BlogPost::withCount(
        //     ['comments', 
        //      'comments as new_comments' => function($query) {
        //         $query->where('created_at', '>=', '2022-11-29 03:06:40');} 
        //     ])->get();
        // dd($post);

        $posts = BlogPost::latest()->withCount('comments')->get();

        return view('posts.index', [
                'posts' => $posts,
                'mostCommented' => BlogPost::withCount('comments')
                    ->orderBy('comments_count', 'desc')
                    ->take(5)->get()
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
        //one way to apply local scope to query (notice lamda after 'comments')
        return view('posts.show', [
            'post' => BlogPost::with(['comments' => function ($query) {
                return $query->latest();
            }])->findOrFail($id),
        ]);

        //here we remove lamda and sorting of comments is done on relationship in the 
        //blog post model (see model)
        return view('posts.show', [
            'post' => BlogPost::with('comments')->findOrFail($id),
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
}
