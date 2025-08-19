<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class MorePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'frames']);
        Permission::create(['name' => 'no_ads']);
        Permission::create(['name' => 'block_chapter']);
        Permission::create(['name' => 'verified_icon']);
        Permission::create(['name' => 'animate_covers']);
        Permission::create(['name' => 'animate_banners']);
    }
}
