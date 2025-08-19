<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = database_path('data/status.json');
        $statuses = json_decode(File::get($json), true);
        $data = [];
        foreach($statuses['status'] as $status){
            $data[] = [
                'name' => $status['nome'],
                'description' => $status['descricao'],
                'updated_at' => now(),
                'created_at' => now()
            ];
        }

        Status::insert($data);
    }
}
