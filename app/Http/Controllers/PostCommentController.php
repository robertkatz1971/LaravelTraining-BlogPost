<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Http\Requests\StoreComment;
use Illuminate\Support\Facades\Auth;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }

    public function store(BlogPost $post, StoreComment $request) {
        $validated = $request->validated();
        
        $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id,
        ]);

        $request->session()->flash('status', 'The comment post was created!');
        return redirect()->back();
    }
}
