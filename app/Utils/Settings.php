<?php

namespace App\Utils;

use App\Models\User;

class Settings
{
    public static function get(): array
    {
        return [
            'name'=> get_settings('app.app_name') ?: 'Manga Reader',
            'url_cdn'=> get_settings('app.app_url_cdn') ?: '/storage',
            'comic_slug'=> get_settings('app.app_comic_slug'),
            'contact_link'=> get_settings('app.app_contact_link'),
            'logo'=> get_settings('app.app_logo'),
            'icon'=> get_settings('app.app_favicon'),
            'vip_banner' => get_settings('app.app_vip_show_banner') ?: false,
            'require_auth' => get_settings('app.app_require_auth') ?: false,
            'warning_channel' => get_settings('app.app_warning_channel'),
            'chapter_show_views' => get_settings('app.app_chapter_show_views') ?: false,
            'ads' => [
                'home_top' => get_settings('ads.ads_home_top'),
                'home_recent' => get_settings('ads.ads_home_recent'),
                'home_new_chapters' => get_settings('ads.ads_home_new_chapters'),
                'home_per_genre' => get_settings('ads.ads_home_per_genre'),
                'home_card' => get_settings('ads.ads_home_card'),
                'home_bottom' => get_settings('ads.ads_home_bottom'),
                'comic_top' => get_settings('ads.ads_comic_top'),
                'comic_bottom' => get_settings('ads.ads_comic_bottom'),
                'reader_top' => get_settings('ads.ads_reader_top'),
                'reader_bottom' => get_settings('ads.ads_reader_bottom'),
            ],
        ];
    }

    public static function find(string $name, $default = null): string|null
    {
        return get_settings($name) ?: $default;
    }
}
