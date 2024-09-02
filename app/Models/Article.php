<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Article extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'uuid';
    public $incrementing = false;
    public static function boot(){
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) \Illuminate\Support\Str::uuid();
        });
    }


    protected $fillable = [
        'id',
        'title',
        'slug',
        'description',
        'image',
        'status',
        'view_count',
        'published_at'
    ];


    // get article comments

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    // get article likes

    public function likes(){
        return $this->hasMany(Like::class);
    }
}
