<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /** @use HasFactory<\Database\Factories\StatusFactory> */
    use HasFactory;

    protected $fillable = ['id', 'name', 'description'];

    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    public function comics()
    {
        return $this->belongsToMany(Comic::class);
    }
}
