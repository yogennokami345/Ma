<?php

namespace App\Services;

use App\Models\Chapter;
use App\Models\Page;
use App\Repositories\ChapterRepository;
use App\Utils\UserVip;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ChapterService
{
    protected $repository;

    public function __construct(ChapterRepository $repository)
    {
        $this->repository = $repository;
    }

    public static function createChapterPages(array $data, int $comicId): Chapter
    {

        return DB::transaction(function () use ($data, $comicId) {
            // $coverPath = Storage::disk(env('FILESYSTEM_DISK'))->put('chapterCover', $data['chapter_cover']);
            $chapter = Chapter::create([
                'comic_id' => $comicId,
                'chapter_cover' => $data['chapter_cover'],
                'chapter_number' => $data['chapter_number'],
                'chapter_title' => $data['chapter_title'] ?? null,
                'chapter_path' => $data['chapter_path'] ?? Str::uuid()->toString(),
            ]);


            $pages = collect($data['page_path'])
                ->map(function ($file, $index) use ($chapter) {
                    return [
                        'chapter_id' => $chapter->id,
                        'page_path' => $file,
                        'page_number' => $index + 1,
                    ];
                })->toArray();

            Page::insert($pages);

            return $chapter;
        });
    }

    public static function createChapter(array $data, int $comicId): Chapter
    {
        return DB::transaction(function () use ($data, $comicId) {
            $chapter = Chapter::create([
                'comic_id' => $comicId,
                'chapter_number' => $data['chapter_number'],
                'chapter_title' => $data['chapter_title'] ?? null,
                'chapter_path' => $data['chapter_path'] ?? Str::uuid()->toString(),
            ]);

            $pages = collect($data['page_path'])
                ->map(function ($file, $index) use ($chapter) {
                    return [
                        'chapter_id' => $chapter->id,
                        'page_path' => $file,
                        'page_number' => $index + 1,
                    ];
                })->toArray();

            Page::insert($pages);

            return $chapter;
        });
    }

    private static function extractChapterInfo(string $folderName): array
    {
        preg_match('/(\d+(?:\.\d+)?)(?:\s*[#-]{2}\s*(.+))?/', $folderName, $matches);

        $chapterNumber = $matches[1] ?? null;
        $chapterTitle = $matches[2] ?? null;

        return [$chapterNumber, $chapterTitle];
    }

    private static function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $filePath = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($filePath) ? self::deleteDirectory($filePath) : unlink($filePath);
        }

        rmdir($dir);
    }


    // public static function multUpZip(string $zipPath, int $comicId): void
    // {
    //     $extractPath = storage_path('app/temp/' . Str::random(10));

    //     if (!file_exists($extractPath)) {
    //         mkdir($extractPath, 0755, true);
    //     }

    //     $zip = new ZipArchive();
    //     $zip->open($zipPath);
    //     $zip->extractTo($extractPath);
    //     $zip->close();

    //     $folders = scandir($extractPath);
    //     foreach ($folders as $folder) {
    //         if ($folder === '.' || $folder === '..') {
    //             continue;
    //         }

    //         $chapterPath = $extractPath . DIRECTORY_SEPARATOR . $folder;

    //         if (is_dir($chapterPath)) {
    //             [$chapterNumber, $chapterTitle] = self::extractChapterInfo($folder);

    //             if (!$chapterNumber) {
    //                 continue;
    //             }

    //             $images = array_filter(scandir($chapterPath), function ($file) use ($chapterPath) {
    //                 return is_file($chapterPath . DIRECTORY_SEPARATOR . $file);
    //             });

    //             $imageUrls = [];
    //             foreach ($images as $image) {
    //                 $sourcePath = $chapterPath . DIRECTORY_SEPARATOR . $image;
    //                 $extension = pathinfo($image, PATHINFO_EXTENSION);
    //                 $randomName = Str::random(20) . '.' . $extension;
    //                 $destinationPath = "pages/{$randomName}";
    //                 Storage::disk(env('FILESYSTEM_DISK'))->put($destinationPath, file_get_contents($sourcePath));
    //                 $imageUrls[] = $destinationPath;
    //             }

    //             self::createChapter([
    //                 'chapter_number' => $chapterNumber,
    //                 'chapter_title' => $chapterTitle,
    //                 'page_path' => $imageUrls,
    //             ], $comicId);
    //         }
    //     }
    //     self::deleteDirectory($extractPath);
    // }

    public static function multUpZip(string $zipPath, int $comicId): void
    {
        $extractPath = storage_path('app/temp/' . Str::random(10));

        if (!file_exists($extractPath)) {
            mkdir($extractPath, 0755, true);
        }

        $zip = new ZipArchive();
        $zip->open($zipPath);
        $zip->extractTo($extractPath);
        $zip->close();

        $folders = scandir($extractPath);
        foreach ($folders as $folder) {
            if ($folder === '.' || $folder === '..') {
                continue;
            }

            $chapterPath = $extractPath . DIRECTORY_SEPARATOR . $folder;

            if (is_dir($chapterPath)) {
                [$chapterNumber, $chapterTitle] = self::extractChapterInfo($folder);

                if (!$chapterNumber) {
                    continue;
                }

                // Lista de arquivos no diretório do capítulo
                $images = array_filter(scandir($chapterPath), function ($file) use ($chapterPath) {
                    return is_file($chapterPath . DIRECTORY_SEPARATOR . $file);
                });

                // Ordena as imagens com base nos números iniciais dos nomes
                usort($images, function ($a, $b) {
                    // Extrai números iniciais do nome do arquivo
                    $getNumber = function ($filename) {
                        $name = pathinfo($filename, PATHINFO_FILENAME);
                        preg_match('/^\d+/', $name, $matches);
                        return isset($matches[0]) ? (int)$matches[0] : 0;
                    };

                    return $getNumber($a) <=> $getNumber($b);
                });

                // Processa as imagens ordenadas
                $imageUrls = [];
                foreach ($images as $image) {
                    $sourcePath = $chapterPath . DIRECTORY_SEPARATOR . $image;
                    $extension = pathinfo($image, PATHINFO_EXTENSION);
                    $randomName = Str::random(20) . '.' . $extension;
                    $destinationPath = "pages/{$randomName}";
                    Storage::disk(env('FILESYSTEM_DISK'))->put($destinationPath, file_get_contents($sourcePath));
                    $imageUrls[] = $destinationPath;
                }

                self::createChapter([
                    'chapter_number' => $chapterNumber,
                    'chapter_title' => $chapterTitle,
                    'page_path' => $imageUrls,
                ], $comicId);
            }
        }
        self::deleteDirectory($extractPath);
    }

    public static function getChapter(string $id): array
    {
        $user = auth()->user();

        $chapter = Chapter::with(['comic:id,title,slug', 'pages'])->where('chapter_path', $id)->firstOrFail();
        $chapter->increment('views');

        $lastChapter = Chapter::where('comic_id', $chapter->comic_id)
            ->orderBy('chapter_number', 'desc')
            ->first();

        $previousChapter = Chapter::where('comic_id', $chapter->comic_id)
            ->where('chapter_number', '<', $chapter->chapter_number)
            ->orderByDesc('chapter_number')
            ->select(['id', 'chapter_number', 'chapter_title', 'chapter_path'])
            ->first();

        $nextChapter = Chapter::where('comic_id', $chapter->comic_id)
            ->where('chapter_number', '>', $chapter->chapter_number)
            ->orderBy('chapter_number')
            ->select(['id', 'chapter_number', 'chapter_title', 'chapter_path'])
            ->first();

        if(!UserVip::subscription_time_valid($user)){
            if (
                $chapter->locked !== null
                && strtotime($chapter->locked) > time()
            ) {
                $chapter = $chapter->toArray();
                $chapter['pages'] = [];
            }
        } else if (
                $chapter->locked !== null
                && strtotime($chapter->locked) > time()
                && !$user->hasPermissionTo('block_chapter')
            ) {
            $chapter = $chapter->toArray();
            $chapter['pages'] = [];
        }

        return [
            'chapter' => $chapter,
            'lastChapter' => $lastChapter,
            'previousChapter' => $previousChapter ?? null,
            'nextChapter' => $nextChapter ?? null,
        ];
    }
}
