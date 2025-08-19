<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use BeyondCode\Comments\Traits\HasComments;

class Chapter extends Model
{
    /** @use HasFactory<\Database\Factories\ChapterFactory> */
    use HasFactory, HasComments;

    protected $fillable = ['comic_id', 'chapter_cover', 'chapter_number', 'chapter_title', 'chapter_path', 'created_at', 'locked', 'views'];

    public function comic()
    {
        return $this->belongsTo(Comic::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    protected static function booted()
    {
        static::deleting(function (self $chapter) {
            foreach ($chapter->pages as $page) {
                if ($page->page_path) {
                    Storage::disk(env('FILESYSTEM_DISK'))->delete($page->page_path);
                }
                $page->delete();
            }

            if ($chapter->chapter_cover){
                Storage::disk(env('FILESYSTEM_DISK'))->delete($chapter->chapter_cover);
            }
        });

        static::updating(function (self $chapter) {
            $originalChapterCover = $chapter->getOriginal('chapter_cover');

            foreach ($chapter->pages as $page) {
                $originalPageUrl = $page->getOriginal('page_path');


                if ($page->isDirty('page_path') && $originalPageUrl) {
                    Storage::disk(env('FILESYSTEM_DISK'))->delete($originalPageUrl);
                }
            }

            if (!empty($originalChapterCover)) {
                Storage::disk(env('FILESYSTEM_DISK'))->delete($originalChapterCover);
            }
        });
    }
}
