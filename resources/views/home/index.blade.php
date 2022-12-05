@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
<h1>Hello World!</h1>

@for ($x = 0; $x < 10; $x++)
    <p>{{ $x }}</p>
@endfor

@php
    $y = true;
@endphp

@while ($y)
    <p>I am not done.</p>

    @php
        $y = random_int(0, 1) ? true : false;
    @endphp
@endwhile
@endsection