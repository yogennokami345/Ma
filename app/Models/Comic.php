<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class Comic extends Model
{
    /** @use HasFactory<\Database\Factories\ComicFactory> */
    use HasFactory, Searchable;

    protected $fillable = ['id', 'banner', 'cover', 'title', 'alternative_name', 'description', 'views', 'is_adult', 'release_date','slug'];

    protected $hidden = ['pivot'];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function statuses()
    {
        return $this->belongsToMany(Status::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_comic', 'comic_id', 'group_id');
    }

    protected static function booted()
    {
        static::deleting(function (self $comic) {

            if($comic->banner)
            {
                Storage::disk(env('FILESYSTEM_DISK'))->delete($comic->banner);
            }

            if ($comic->cover)
            {
                Storage::disk(env('FILESYSTEM_DISK'))->delete($comic->cover);
            }
        });

        static::updating(function (self $comic) {
            $originalBanner = $comic->getOriginal('banner');
            $originalCover = $comic->getOriginal('cover');

            if ($comic->isDirty('banner') && $originalBanner)
            {
                Storage::disk(env('FILESYSTEM_DISK'))->delete($originalBanner);
            }

            if ($comic->isDirty('cover') && $originalCover)
            {
                Storage::disk(env('FILESYSTEM_DISK'))->delete($originalCover);
            }
        });
    }

    public function toSearchableArray()
    {
        return [
            'title'            => $this->title,
            'alternative_name' => $this->alternative_name,
            'description'      => $this->description,
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class,  'comic_user_like', 'comic_id', 'user_id');
    }
}
