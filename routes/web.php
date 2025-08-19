<?php

use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\DiscordAuthController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeComicController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MultiUpController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShoppingController;
use App\Http\Controllers\ShowByGenreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SitemapController;
use App\Services\DiscordAuthService;
use App\Utils\Settings;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

// Rota de login permanece pública
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::get('/register', [LoginController::class, 'register'])->name('register');

Route::get('/auth/discord', function () {

    return Socialite::driver('discord')->redirect();

})->name('discordAuth');

Route::get('/discord/callback', [DiscordAuthController::class, 'index']);

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::prefix('policies')->group(function(){
    Route::inertia('/dmca', 'Policies/DMCA/Show')->name('dmca');
    Route::inertia('/privacypolicy', 'Policies/PrivacyPolicy/Show')->name('privacy');
});

$applyAuthMiddleware = Settings::find('app.app_require_auth', false);
$middleware = $applyAuthMiddleware ? ['auth'] : [];
Route::middleware($middleware)->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    $comicSlug = Settings::find('app.app_comic_slug') ?: 'comic';
    Route::get('/' . $comicSlug . '/{slug}', [ComicController::class, 'index'])->name('comic');
    Route::get('chapter/{id}', [ChapterController::class, 'index'])->name('chapter');
    Route::post('/user/update/profile', [UserController::class, 'store'])->name('updateProfile');
    Route::get('/user/{id}', [UserController::class, 'index'])->name('user');
    Route::post('/user/{user}/follow', [FollowerController::class, 'toggle'])->name('user.follow');
    Route::get('/show/genre/{id}', [ShowByGenreController::class, 'index'])->name('showComicByGenres');
    Route::get('/show/genres', [ShowByGenreController:: class, 'showGenres'])->name('showGenres');
    Route::post('/comic/like/{slug}', [LikeComicController::class, 'index'])->name('likecomic');

    Route::prefix('comments')->group(function(){
        Route::post('/chapter/{id}', [CommentsController::class, 'chapterStore'])->name('chapter.comment');
        Route::post('/response/{id}', [CommentsController::class, 'reponseChapterStore'])->name('chapter.response');
        Route::delete('/{id}', [CommentsController::class, 'deleteChapterComment'])->name('chapter.comment.delete');
    });

    Route::get('/shop/vip', [ShoppingController::class, 'index'])->name('shop');
    Route::get('/callback/pay/{id}', [ShoppingController::class, 'callBackPay'])->name('callbackPay');
    Route::get('/pay/{id}', [ShoppingController::class, 'pay'])->name('pay');
    Route::get('/giftcards/redeem/{id}', [GiftCardController::class, 'index'])->name('giftcards');
    Route::post('/giftcards/redeem', [GiftCardController::class, 'redeem'])
    ->name('redeem');

});


Route::group(['prefix' => 'api'], function () {
    Route::post('/login', [LoginController::class, 'store'])->name('loginApi');
    Route::post('/register', [LoginController::class, 'storeRegister'])->name('registerApi');
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::post('/library/{comic}', [ComicController::class, 'library'])->name('addToLibrary');
    Route::post("/entrar", [LoginController::class, "loginApi"]);
    Route::middleware(["auth:sanctum"])->group(function () {
        Route::post("/multiup", [MultiUpController::class, "store"]);
        Route::get("/getchapters/{slug}", [MultiUpController::class, "show"]);
        Route::get("/getpages/{id}", [MultiUpController::class, "showPages"]);
    });
});
