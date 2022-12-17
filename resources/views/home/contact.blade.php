@extends('layouts.app')

@section('title', 'Contact Page')

@section('content')
<h1>Contact Page!</h1>

@can('isAdmin')
  <a href="{{ route('home.secret') }}">Go to special contact details</a>
@endcan
@endsection