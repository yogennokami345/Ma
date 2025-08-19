<?php

namespace App\Services;

use App\Models\GiftCard;
use App\Models\Plan;
use App\Models\User;
use App\Repositories\UserRepository;

use function Psy\debug;

class UserService
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getOrders($user)
    {
        $userRecord = User::with('orders')->where('user_path', $user)->firstOrFail();
        $orders = $userRecord->orders;
        
        foreach ($orders as $order) {
            $planName = Plan::where('id', $order->plan_id)->firstOrFail();
            $order->plan_id = $planName;
        }
        
        return $orders;
    }

    public function getUserGiftCards($user)
    {
        $userRecord = User::with('giftCards')->where('user_path', $user)->firstOrFail();
        $giftCards = $userRecord->giftCards;
        
        foreach ($giftCards as $giftCard) {
            if(isset($giftCard->owner_user_id)){
                $ownerProfile = User::where('id', $giftCard->owner_user_id)->firstOrFail();
                $giftCard->owner_user_id = $ownerProfile;
            }
            $codeWithUrl = env('APP_URL') . '/giftcards/redeem/' . $giftCard->code;
            $giftCard->code = $codeWithUrl;

            $planName = Plan::where('id', $giftCard->plan_id)->firstOrFail();
            $giftCard->plan_id = $planName;
        }
        
        return $giftCards;
    }

    public function updateProfile($user, $banner, $cover, $description)
    {
        if(isset($banner)){
            $this->repository->updateBanner($user, $banner);
        }
        if(isset($cover)){
            $this->repository->updateCover($user, $cover);
        }
        if(isset($description)){
            $this->repository->updateDescription($user, $description);
        }
    }
}