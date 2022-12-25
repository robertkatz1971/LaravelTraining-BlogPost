@extends('layouts.app')

@section('title', 'Blog Posts')

@section('content')
<div class="row">
    <div class="col-8">
        @forelse ($posts as $key => $post )
    @include('posts.partials.post')
 @empty
     <div>No Post found!</div>
 @endforelse
    </div>
    <div class="col-4">
        <div class="container">

            <x-card title='Most Active Users All Time' subtitle='Who has been blogging the most' >
                @slot('items', collect($mostActive)->map(function($user) {
                    return  $user->name . ' (' . $user->blog_post_count  . ')';
                }))
            </x-card>

            <x-card title='Most Active Users Last Month' subtitle='Who has been blogging the most in last month' >
                @slot('items', collect($mostActiveLastMonth)->map(function($user) {
                    return  $user->name . ' (' . $user->blog_post_count  . ')';
                }))
            </x-card>

            <x-card title='Most Commented Blog Post' subtitle='Top 5 commented posts' >
                @slot('items')
                    @foreach ($mostCommented as $post)
                        <li class="list-group-item">
                            <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                                {{ $post->title }}
                            </a>
                        </li>
                    @endforeach
                @endslot
            </x-card>
        </div>
    </div>
 
</div>
  
@endsection