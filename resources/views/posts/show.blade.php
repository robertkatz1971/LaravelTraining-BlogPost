@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="row">
    <div class="col-8">
        <h1>{{ $post['title'] }}
            {{ $visible = $post->created_at->diffInMinutes() < 150 }}
            <x-badge :visible=$visible>
                New!
            </x-badge>
        </h1>

        <p>{{ $post['content'] }}</p>
        <x-updated date='{{ $post->created_at->diffForHumans() }}' name='{{ $post->user->name }}'>
        </x-updated>
        <x-updated date='{{ $post->updated_at->diffForHumans() }}'>
            Updated
        </x-updated>
        <x-tags>
            @slot('tags', $post->tags)
        </x-tags>
        <p>Currently read by {{ $counter }} people.</p>
        <h4>Comments</h4>
        @forelse ($post->comments as $comment)
            <p>
                {{ $comment->content }}, 
            </p>
            <x-updated date='{{ $comment->created_at->diffForHumans() }}' name='{{ $comment->user->name }}'>
            </x-updated>
        @empty
            <div>No Comments found!</div>
        @endforelse
    </div>
    <div class="col-4">
        @include('posts._activity')
    </div>
</div>
@endsection