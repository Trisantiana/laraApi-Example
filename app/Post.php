<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['id'];

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('id', 'DESC');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
