<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'days',
        'description',
        'role_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
