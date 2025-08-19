<?php

namespace App\Utils;

use App\Models\User;

class UserVip
{
    public static function subscription_time_valid(?User $user)
    {
        if ($user === null) {
            return false;
        }
        if(!$user->subscription_start || !$user->subscription_end) {
            return false;
        }
        $now = now();
        return $user->subscription_start <= $now && $user->subscription_end >= $now;
    }
}
