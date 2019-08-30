<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = ['name'];

    public function books()
    {
        return $this->belongsToMany('App\Models\Book');
    }

    public function magazines()
    {
        return $this->belongsToMany('App\Models\Magazine');
    }
}
