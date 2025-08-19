<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Hero extends Model
{
    /** @use HasFactory<\Database\Factories\HeroFactory> */
    use HasFactory;

    protected $fillable = ['hero_path', 'hero_link'];

    protected static function booted()
    {
        static::deleting(function(self $hero)
        {
            if ($hero->hero_path)
            {
                Storage::disk(env('FILESYSTEM_DISK'))->delete($hero->hero_path);
            }
        });

        static::updating(function(self $hero)
        {
            $originalHero = $hero->getOriginal('hero_path');

            if ($hero->isDirty('hero_path') && $originalHero)
            {
                Storage::disk(env('FILESYSTEM_DISK'))->delete($originalHero);
            }
        });
    }
}
