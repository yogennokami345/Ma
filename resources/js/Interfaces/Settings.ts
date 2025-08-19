interface Settings {
    name: string;
    url_cdn: string;
    logo?: string | null;
    icon?: string | null;
    vip_banner: boolean;
    contact_link: string | null;
    warning_channel: string | null;
    chapter_show_views: boolean;
    ads: {
        home_top?: string | null;
        home_recent?: string | null;
        home_new_chapters?: string | null;
        home_per_genre?: string | null;
        home_card?: string | null;
        home_bottom?: string | null;
        comic_top?: string | null;
        comic_bottom?: string | null;
        reader_top?: string | null;
        reader_bottom?: string | null;
    };
}
