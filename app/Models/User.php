<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Support\Str;
use BeyondCode\Comments\Contracts\Commentator;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, Commentator
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, Searchable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'banner',
        'cover',
        'user_path',
        'name',
        'email',
        'password',
        'description',
        'subscription_start',
        'subscription_end',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email',
        'email_verified_at',
    ];

    public function needsCommentApproval($model): bool
    {
        return false;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyPermission([
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
        ]);
    }

    public function comics()
    {
        return $this->belongsToMany(Comic::class, 'comic_user', 'user_id', 'comic_id');
    }

    public function likedComics()
    {
        return $this->belongsToMany(Comic::class, 'comic_user_like', 'user_id', 'comic_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    /**
     * Os seguidores deste usuário
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id')
            ->withTimestamps();
    }

    public function giftCards()
    {
        return $this->hasMany(GiftCard::class, 'gift_create_by_user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'user_group', 'user_id', 'group_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->user_path = Str::uuid(); // Gera UUID único ao criar usuário
        });
    }
}
