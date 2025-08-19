interface Chapter {
    id: number;
    chapter_cover: string;
    chapter_number: number;
    chapter_title: string;
    chapter_path: string;
    created_at: string;
    updated_at: string;
    locked: string;
    views: number;
    pages: Page[];
}
