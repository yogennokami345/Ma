<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserRepository extends AbstractRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function updateBanner(User $user, UploadedFile $bannerFile): string
    {
        if ($user->banner && Storage::disk()->exists($user->banner)) {
            Storage::disk()->delete($user->banner);
        }

        $bannerPath = $bannerFile->store('user/banners', env('FILESYSTEM_DISK'));
        $user->banner = $bannerPath;
        $user->save();

        return $bannerPath;
    }

    /**
     * Atualiza a capa (cover) do usuário.
     *
     * @param User $user
     * @param UploadedFile $coverFile
     * @return string O caminho da capa armazenada.
     */
    public function updateCover(User $user, UploadedFile $coverFile): string
    {
        if ($user->cover && Storage::disk()->exists($user->cover)) {
            Storage::disk()->delete($user->cover);
        }
        $coverPath = $coverFile->store('user/covers', env('FILESYSTEM_DISK'));
        $user->cover = $coverPath;
        $user->save();

        return $coverPath;
    }

    public function updateDescription(User $user, $description)
    {
        $this->update($user->id, ['description' => $description]);
    }
}
