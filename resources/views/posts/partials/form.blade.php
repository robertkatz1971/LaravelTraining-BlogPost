<div class="form-group">
    <label for="title">Title</label>
    <input id="title" class= "form-control" type="text" name="title" value="{{ old('title', optional($post ?? null)->title) }}">
</div>
@error('title')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
<div class="form-group">
    <label for="content">Content</label>
    <textarea id="content" class= "form-control" name="content" rows="3" >
        {{ old('content', optional($post ?? null)->content) }}
    </textarea>
</div>
@error('content')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
<div class="form-group">
    <label for="thumbnail">Thumbnail</label>
    <input id="thumbnail" 
        class= "form-control-file" 
        type="file" 
        name="thumbnail" >
</div>
@error('thumbnail')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

