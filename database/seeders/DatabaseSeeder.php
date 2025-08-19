<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        
        $this->call(GenreSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(MorePermissionsSeeder::class);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'Admin',
            'user_path' => Str::uuid(),
        ]);
    
        $admin->assignRole('admin');
    }
}
