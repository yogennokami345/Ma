<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = database_path('data/genres.json');
        $genres = json_decode(File::get($json), true);
        $data = [];
        foreach($genres['genres'] as $genre){
            $data[] = [
                'name' => $genre,
                'updated_at' => now(),
                'created_at' => now()
            ];
        }

        Genre::insert($data);
    }
}
