@extends('layouts.app')

@section('content')
    <form action="" 
        method="POST" 
        enctype="multipart/form-data" 
        action="{{ route('users.update', ['user' => $user->id]) }}"
        class="form-horizontal"
    >
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-4">
                <img class="img-thumbnail avatar" src="" alt="">

                <div class="card mt-4">
                    <div class="card-body">
                        <h6>Upload a different photo</h6>
                        <input type="file" 
                            class="form-control-file" 
                            name="avatar" 
                        />
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    <label for="name">Name: </label>
                    <input name="name" class="form-control" value="" type="text">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Save Changes">
                </div>
            </div>
        </div>
    </form>
@endsection