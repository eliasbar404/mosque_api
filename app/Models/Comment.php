<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Comment extends Model
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
        'article_id',
        'member_id',
        'comment',
        'status'
    ];


    // get user

    public function member(){
        return $this->belongsTo(Member::class);
    }

}
