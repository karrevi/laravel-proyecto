<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Snnipets extends Model
{
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo('\App\User');
    }
    function comments()
    {
        return $this->morphMany('\App\Comment', 'commentable');
    }
}
