<?php

namespace App\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function blogPost() {
        return $this->belongsTo('App\Models\BlogPost');
    }

    public function scopeLatest(Builder $query) {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }
}
