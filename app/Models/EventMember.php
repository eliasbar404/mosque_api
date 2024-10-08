<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EventMember extends Model
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
        'event_id',
        'member_id',
        'note',
        'status',
    ];

    public function event(){
        $this->belongsTo(Event::class);
    }

    public function member(){
        $this->belongsTo(Member::class);
    }
}
