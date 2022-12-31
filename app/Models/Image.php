<?php

namespace App\Models;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['path'];
    
    public function imageable() {
        return $this->morphTo();
    }

    public function url() {
        return Storage::url($this->path);
    }
}
