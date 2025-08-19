<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::create(['name' => 'create_comic']);
        Permission::create(['name' => 'edit_comic']);
        Permission::create(['name' => 'delete_comic']);
        Permission::create(['name' => 'upload_chapter']);
        Permission::create(['name' => 'delete_chapter']);
        Permission::create(['name' => 'edit_chapter']);
        Permission::create(['name' => 'delete_genre']);
        Permission::create(['name' => 'create_genre']);
        Permission::create(['name' => 'view_genre']);
        Permission::create(['name' => 'update_genre']);
        Permission::create(['name' => 'edit_genre']);
        Permission::create(['name' => 'gift_card_view']);
        Permission::create(['name' => 'create_gift_card']);
        Permission::create(['name' => 'edit_gift_card']);
        Permission::create(['name' => 'delete_gift_card']);
        Permission::create(['name' => 'comic_view']);
        Permission::create(['name' => 'delete_hero']);
        Permission::create(['name' => 'edit_hero']);
        Permission::create(['name' => 'create_hero']);
        Permission::create(['name' => 'view_hero']);
        Permission::create(['name' => 'view_plan']);
        Permission::create(['name' => 'create_plan']);
        Permission::create(['name' => 'edit_plan']);
        Permission::create(['name' => 'delete_plan']);
        Permission::create(['name' => 'view_group']);
        Permission::create(['name' => 'create_group']);
        Permission::create(['name' => 'edit_group']);
        Permission::create(['name' => 'delete_group']);
        Permission::create(['name' => 'view_status']);
        Permission::create(['name' => 'create_status']);
        Permission::create(['name' => 'edit_status']);
        Permission::create(['name' => 'delete_status']);
        Permission::create(['name' => 'view_user']);
        Permission::create(['name' => 'create_user']);
        Permission::create(['name' => 'edit_user']);
        Permission::create(['name' => 'delete_user']);
        Permission::create(['name' => 'view_order']);
        Permission::create(['name' => 'view_dashboard']);
        Permission::create(['name' => 'manage_settings']);
        Permission::create(['name' => 'manage_roles']);

        Permission::create(['name' => 'manage_users']);

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'create_comic',
            'edit_comic',
            'delete_comic',
            'manage_users',
            'upload_chapter',
            'delete_genre',
            'create_genre',
            'view_genre',
            'update_genre',
            'edit_genre',
            'edit_chapter',
            'delete_chapter',
            'gift_card_view',
            'view_plan',
            'create_plan',
            'edit_plan',
            'delete_plan',
            'delete_hero',
            'edit_hero',
            'create_hero',
            'view_hero',
            'comic_view',
            'create_gift_card',
            'edit_gift_card',
            'delete_gift_card',
            'view_group',
            'create_group',
            'edit_group',
            'delete_group',
            'view_status',
            'create_status',
            'edit_status',
            'delete_status',
            'view_user',
            'create_user',
            'edit_user',
            'delete_user',
            'view_order',
            'view_dashboard',
            'manage_settings',
            'manage_roles',
        ]);

        $editor = Role::create(['name' => 'editor']);
        $editor->givePermissionTo([
            'create_comic',
            'edit_comic',
        ]);

        Role::create(['name' => 'user']);
    }
}
