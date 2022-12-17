<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\PostsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
$posts = [
    1 => [
        'title' => 'Intro to JavaScript',
        'content' => 'This is a short intro to JavaScript',
        'is_new' => true,
        'has_comments' => true
    ],
    2 => [
        'title' => 'Intro to PHP',
        'content' => 'This is a short intro to PHP',
        'is_new' => false
    ],
    3 => [
        'title' => 'Intro to .NET',
        'content' => 'This is a short intro to .NET',
        'is_new' => true,
        'has_comments' => true
    ],
    4 => [
        'title' => 'Intro to Python',
        'content' => 'This is a short intro to Python',
        'is_new' => false
    ],
];

Auth::routes();

Route::get('/', [HomeController::class, 'contact']);
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');
Route::get('/secret', [HomeController::class, 'secret'])->name('home.secret')->middleware('can:isAdmin');
Route::get('/about', AboutController::class)->name('home.about');

Route::resource('posts', PostsController::class);

// Route::get('/posts', function () use ($posts) {
//     return view('posts.index', ['posts' => $posts]);
// } )->name('post.index');

// Route::get('/posts/{id}', function ($id) use ($posts) {

//     abort_if(!isset($posts[$id]), 404);
//     return view('posts.show', ['post' => $posts[$id]]);

// })->name('posts.show');

// Route::get('/posts/{id}', function ($id) use ($posts) {
//     abort_if(!isset($posts[$id]), 404);
//     return view('posts.show', ['post' => $posts[$id]]);
// })->name('posts.show');

//? makes parameter optional
Route::get('/recent-posts/{days_ago?}', function($daysAgo = 20) {
    return 'Here are post from ' . $daysAgo . ' days ago.';
})->name('posts.recent.index');


//example of using route grouping (including shared prefix and root name)
Route::prefix('/fun')->name('fun.')->group(function() use ($posts) {
    Route::get('/responses', function() use ($posts) {
        return response($posts, 201)
            ->header('Content-Type', 'application/json')
            ->cookie('MY_COOKIE', 'Robert Katz', 3600);
    })->name('responses');
    Route::get('/json', function() use ($posts) {
        return response()->json($posts);
    })->name('json');
    Route::get('back', function() {
        return back();
    })->name('back');
    Route::get('redirect', function() {
        return redirect()->route('posts.show', 1);
    })->name('redirect');
    Route::get('request', function() {
        dd(request()->all());
    })->name('request');
});




