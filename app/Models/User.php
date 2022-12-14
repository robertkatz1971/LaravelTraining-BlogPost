<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Support\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function blogPost() {
       return  $this->hasMany('App\Models\BlogPost');
    }

    public function comments() {
       return $this->hasMany(Comment::class);
    }

    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function scopeMostBlogPosts (Builder $query) {
        return $query->withCount('BlogPost')->orderBy('blog_post_count', 'desc');
    }

    public function scopeMostBlogPostsLastMonth (Builder $query) {
        return $query->withCount(['BlogPost' => function (Builder $query) {
            $query->where(static::CREATED_AT, '>', now()->subMonth());
        }])->has('BlogPost', '>=', 2)
           ->orderBy('blog_post_count', 'desc');
    }
}
