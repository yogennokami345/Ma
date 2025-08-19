<?php

namespace Database\Factories;

use App\Models\Genre;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Http;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comic>
 */
class ComicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(3);

        $bannerPath = 'banner/' . fake()->uuid() . '.svg';
        $coverPath = 'covers/' . fake()->uuid() . '.svg';

        $bannerContent = Http::get("https://placehold.co/900x600/31343C/EEE")->body();
        $coverContent = Http::get("https://placehold.co/600x900/31343C/EEE")->body();

        Storage::disk(env('FILESYSTEM_DISK'))->put($bannerPath, $bannerContent);
        Storage::disk(env('FILESYSTEM_DISK'))->put($coverPath, $coverContent);
        return [
            'banner' => $bannerPath,
            'cover' => $coverPath,
            'title' => $title,
            'description' => $this->faker->paragraph(),
            'views' => $this->faker->numberBetween(0, 10000),
            'release_date' => $this->faker->optional()->year(),
            'slug' => Str::slug($title),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($model) {
            $genres = Genre::inRandomOrder()->limit(3)->pluck('id');
            $model->genres()->attach($genres);

            $statuses = Status::inRandomOrder()->limit(1)->pluck('id');
            $model->statuses()->attach($statuses);
        });
    }
}
