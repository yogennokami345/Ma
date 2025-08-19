<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    /** @use HasFactory<\Database\Factories\GiftCardFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'owner_user_id',
        'usage_limit',
        'usage_count',
        'expires_at',
        // 'subscription_days',
        'plan_id',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'active' => 'boolean',
        ];
    }

    public function isExpired(): bool
    {
        if (is_null($this->expires_at)) {
            return false;
        }
        return $this->expires_at->isPast();
    }

    public function hasUsagesLeft(): bool
    {
        return $this->usage_count < $this->usage_limit;
    }

    public function isValidToUse(): bool
    {
        if (! $this->active) {
            return false;
        }
        if ($this->isExpired()) {
            return false;
        }
        if (! $this->hasUsagesLeft()) {
            return false;
        }
        return true;
    }
    
    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function createByUser()
    {
        return $this->belongsTo(User::class, 'gift_create_by_user_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
