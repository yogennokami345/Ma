<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name'];

    public function comics()
    {
        return $this->belongsToMany(Comic::class, 'group_comic', 'group_id', 'comic_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_group', 'group_id', 'user_id');
    }
}
