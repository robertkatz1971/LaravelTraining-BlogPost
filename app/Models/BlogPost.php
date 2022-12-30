<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\User;
use App\Models\Image;
use App\Models\Comment;
use App\Scopes\DeletedAdminScope;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'content', 'user_id'];

    public function comments() {
        //applying local scope to order comments by created date (see: comment::scopeLatest)
        return $this->hasMany(Comment::class)->latest();
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function image() {
        return $this->hasOne(Image::class);
    }

    public function scopeLatestWithRelations(Builder $query) {
        return $query->latest()
            ->with('user')
            ->withCount('comments')
            ->with('tags');
    }

    public function scopeLatest(Builder $query) {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query) {
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    //subscribing to event and handling deleting related records.   
    public static function boot() {
        //adding before boot in this specific example because useSoftDeletes attribute prevents
        //withTrashed() from working properly.
        
        static::addGlobalScope(new DeletedAdminScope);

        parent::boot();

        static::deleting(function (BlogPost $post) {
            $post->comments()->delete();
            Cache::tags(['blog-post'])->forget("blog_post_{$post->id}");
        });

        static::restoring(function (BlogPost $post) {
            $post->comments()->restore();
        });

        static::updating(function (BlogPost $post) {
            Cache::tags(['blog-post'])->forget("blog_post_{$post->id}");
        });
    }
}
