<?php

namespace App\Models;

use App\Models\User;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['content', 'user_id', 'blog_post_id'];

    public function blogPost() {
        return $this->belongsTo(BlogPost::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scopeLatest(Builder $query) {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public static function boot() {
        parent::boot();

        static::creating(function (Comment $comment) {
            Cache::tags(['blog-post'])->forget("blog_post_{$comment->blog_post_id}");
            Cache::tags(['blog-post'])->forget('blog-post-most-commented');
            
        });
    }

}
