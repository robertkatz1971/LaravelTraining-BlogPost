<div class="mb-2 mt-2">
    @auth
        <form action="{{ route('post.comment.store', ['post' => $post]) }}" method="POST">
            @csrf
            <div class="form-group">
                <textarea id="content" class= "form-control" name="content" >{{ old('content') }}</textarea>
            </div>
            @error('content')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div><input type="submit" value="Add Comment" class="btn-primary btn-block"></div>
        </form>
    @else
        <a href="{{ route('login') }}">Sign-in</a> to post comments!
    @endauth
    <hr> 
</div>

