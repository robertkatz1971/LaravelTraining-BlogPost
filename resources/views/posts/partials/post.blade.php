@if ($post->trashed()) 
    <del>
@endif
<h3><a href="{{ route('posts.show', ['post' => $post->id]) }}" class={{ $post->trashed() ? 'text-muted' : '' }}>{{ $post->title }}</a></h3>
@if ($post->trashed())
    </del>
@endif
<p>Created at {{ $post->created_at }} by {{ $post->user->name }}</p>
@if($post->comments_count) 
    <p>{{ $post->comments_count . " comment"}}{{ $post->comments_count == 1 ? "": "s" }}</p>
@else
    <p>No comments yet</p>
@endif

<div class="mb-3">
    @can('update', $post)
        <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit</a>
    @endcan
 
    @can('delete', $post)
        @if ($post->trashed()) 
            @can('restore', $post)
                <form  class="d-inline" action="{{ route('posts.restore', ['id' => $post->id]) }}" method="POST">
                    @csrf
                    <input type="submit" value="Restore" class="btn btn-danger">
                </form>
            @endcan
        @else
            <form  class="d-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-primary">
            </form>   
        @endif
    @endcan
   
</div>