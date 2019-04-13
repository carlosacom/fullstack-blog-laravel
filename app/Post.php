<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    public function category()
    {
        return $this->BelongsTo('App\Category', 'category_id');
    }
    public function user()
    {
        return $this->BelongsTo('App\User', 'user_id');
    }
}
