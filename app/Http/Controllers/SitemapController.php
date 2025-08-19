<?php

namespace App\Http\Controllers;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Comic;
use App\Models\Chapter;
use App\Utils\Settings;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create();

        $sitemap->add(Url::create('/')->setPriority(1.0)->setChangeFrequency('daily'));

        $comics = Comic::all();
        $slug = Settings::find('app.app_comic_slug') ?: 'comic';
        foreach ($comics as $comic) {
            $sitemap->add(
                Url::create('/'. $slug .'/' . $comic->slug)
                    ->setPriority(0.9)
                    ->setChangeFrequency('daily')
            );
        }

        $chapters = Chapter::all();
        foreach ($chapters as $chapter) {
            $sitemap->add(
                Url::create('/chapter/' . $chapter->chapter_path)
                    ->setPriority(0.7)
                    ->setChangeFrequency('daily')
            );
        }

        $sitemap->add(Url::create('/about')->setPriority(0.8)->setChangeFrequency('monthly'));
        $sitemap->add(Url::create('/contact')->setPriority(0.8)->setChangeFrequency('monthly'));

        return $sitemap->toResponse(request());
    }
}
