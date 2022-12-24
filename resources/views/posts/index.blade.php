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
            <div class="row">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                    <h5 class="card-title">Most Active All Time</h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                        Who has been blogging the most
                    </h6>
                    </div>
                    <ul class="list-group list-group-flush">
                    @foreach ($mostActive as $user)
                        <li class="list-group-item">
                            {{ $user->name . ' (' . $user->blog_post_count  . ')' }}
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>

            <div class="row mt-4">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                    <h5 class="card-title">Most Active Last Month</h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                        Who has been blogging the most in last month
                    </h6>
                    </div>
                    <ul class="list-group list-group-flush">
                    @foreach ($mostActiveLastMonth as $user)
                        <li class="list-group-item">
                            {{ $user->name . ' (' . $user->blog_post_count  . ')' }}
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
           
            <div class="row mt-4">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                    <h5 class="card-title">Most Commented</h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                        What people are currently talking about
                    </h6>
                    </div>
                    <ul class="list-group list-group-flush">
                    @foreach ($mostCommented as $post)
                        <li class="list-group-item">
                        <a href="{{ route('posts.show', ['post' => $post->id]) }}"">{{ $post->title }}</a>    
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
 
</div>
  
@endsection