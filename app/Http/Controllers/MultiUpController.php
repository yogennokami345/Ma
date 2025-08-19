<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Page;
use App\Services\ChapterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MultiUpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    private static function createChapter(array $data, int $comicId, string $chapterNumber): Chapter
    {
        return DB::transaction(function () use ($data, $comicId, $chapterNumber) {
            \Log::info("Creating chapter in DB", ['comicId' => $comicId, 'chapterNumber' => $chapterNumber]);

            $chapter = Chapter::create([
                'comic_id' => $comicId,
                'chapter_number' => $chapterNumber,
                'chapter_title' => null,
                'chapter_path' => Str::uuid()->toString(),
            ]);

            \Log::info("Chapter created successfully", ['chapterId' => $chapter->id]);

            $pages = collect($data)
                ->map(function ($file, $index) use ($chapter) {
                    return [
                        'chapter_id' => $chapter->id,
                        'page_path' => $file,
                        'page_number' => $index + 1,
                    ];
                })->toArray();

            \Log::info("Inserting pages into DB", ['pages' => $pages]);

            Page::insert($pages);

            return $chapter;
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "manga_slug" => "required",
            "chapter" => "required",
            "files" => "required",
            "files.*" => "mimes:jpg,jpeg,png,webp",
        ]);

        try {
            $manga = Comic::where("slug", $request->manga_slug)->firstOrFail();
            $chapter = $request->chapter;
            $data = [];
            $user = auth()->user();

            if (!$user || !($user->hasRole("admin") || $user->hasRole("editor"))) {
                return response()->json(
                    ["error" => "Você não tem permissão para fazer upload de capítulos"],
                    403
                );
            }

            if ($request->hasFile("files")) {
                foreach ($request->file("files") as $file) {
                    if ($file->isValid()) {
                        $sourcePath = $file->getPathname();
                        $extension = $file->getClientOriginalExtension();
                        $randomName = Str::random(20) . "." . $extension;
                        $destinationPath = "pages/{$randomName}";

                        // Log para verificar o upload
                        \Log::info("Uploading file to R2: {$destinationPath}");

                        Storage::disk('r2')->put(
                            $destinationPath,
                            file_get_contents($sourcePath)
                        );

                        $data[] = $destinationPath;
                    }
                }
            } else {
                return response()->json(["error" => "No files were uploaded"], 400);
            }

            // Log para verificar os dados antes de criar o capítulo
            \Log::info("Creating chapter with data: ", ['data' => $data, 'comicId' => $manga->id, 'chapterNumber' => $chapter]);

            $createdChapter = self::createChapter($data, $manga->id, $chapter);

            return response()->json(
                ["message" => "Upload concluído com sucesso"],
                200
            );
        } catch (\Exception $e) {
            // Log para capturar erros
            \Log::error("Error in store method: " . $e->getMessage());
            return response()->json(["error" => "Ocorreu um erro ao processar o upload"], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $manga = Comic::with("chapters")->where("slug", $slug)->firstOrFail();

        return response()->json($manga);
    }

    public function showPages(string $id)
    {
        $chapter = Chapter::with("pages")
            ->where("chapter_path", $id)
            ->firstOrFail();

        return response()->json($chapter);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
