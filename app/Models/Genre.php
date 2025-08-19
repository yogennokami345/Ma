<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    /** @use HasFactory<\Database\Factories\GenreFactory> */
    use HasFactory;

    protected $fillable = ['id','name'];

    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    public function comics()
    {
        return $this->belongsToMany(Comic::class);
    }
}
