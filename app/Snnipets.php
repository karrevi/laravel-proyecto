<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Snnipets extends Model
{
    protected $fillable = [
        'name', 'code_snnipets', 'extension', 'user_id'
    ];
    public function user()
    {
        return $this->belongsTo('\App\User');
    }
    public function comments()
    {
        return $this->morphMany('\App\Comment', 'commentable');
    }
}
