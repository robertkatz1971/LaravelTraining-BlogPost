<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'content', 'user_id'];

    public function comments() {
        //applying local scope to order comments by created date (see: comment::scopeLatest)
        return $this->hasMany('App\Models\Comment')->latest();
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function scopeLatest(Builder $query) {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    //subscribing to event and handling deleting related records.   
    public static function boot() {
        parent::boot();

        //static::addGlobalScope(new LatestScope);
        
        static::deleting(function (BlogPost $post) {
            $post->comments()->delete();
        });

        static::restoring(function (BlogPost $post) {
            $post->comments()->restore();
        });
    }
}
