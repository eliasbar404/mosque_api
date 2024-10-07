<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Event extends Model
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
        'published_at',
        'start_time',
        'end_time',
    ];


    // public function eventMembers(){
    //     $this->hasMany(EventMember::class);
    // }

    public function members()
{
    return $this->belongsToMany(Member::class, 'event_members')->withPivot('note','status','id');
}
}
