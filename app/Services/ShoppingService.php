<?php

namespace App\Services;

use App\Payments\Livepix\Livepix;
use App\Repositories\OrderRepository;
use App\Repositories\ShoppingRepository;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ShoppingService
{
    protected $repository;
    protected $orderRepository;
    protected $paymentLivePix;

    public function __construct(ShoppingRepository $repository, OrderRepository $orderRepository, Livepix $paymentLivePix)
    {
        $this->repository = $repository;
        $this->orderRepository = $orderRepository;
        $this->paymentLivePix = $paymentLivePix;
    }

    public function getPlans()
    {
        return $this->repository->all(50);
    }

    public function getPlan($id)
    {
        return $this->repository->findById($id);
    }

    public function createOrder($data)
    {
        $data['id'] = Str::uuid();

        $paymentResponse = $this->paymentLivePix->createPayment($data['amount'], $data['id']);

        $data['payment_id'] = $paymentResponse['reference'];

        $this->orderRepository->create([
            'id' => $data['id'],
            'plan_id' => $data['plan_id'],
            'user_id' => $data['user_id'],
            'payment_id' => $data['payment_id'],
            'paid' => false,
        ]);

        return $paymentResponse['redirectUrl'];
    }

    public function verifyPayment($payment_id)
    {
        $order = $this->orderRepository->findById($payment_id);

        if ($order->paid) return;

        $payment = $this->paymentLivePix->consultPayment($order->payment_id);


        $order->update([
            'paid'=> true,
        ]);

        $user = $order->user;

        $plan = $order->plan;

        $role = $plan->role;

        $user->syncRoles($role);

        $now = Carbon::now();
        $subscription_end = $user->subscription_end ? Carbon::parse($user->subscription_end) : null;
        if (is_null($subscription_end) || $subscription_end->isPast()) {
            $user->subscription_start = $now;
            $user->subscription_end   = $now->clone()->addDays($plan->days);
        } else {
            $user->subscription_end = $subscription_end->addDays($plan->days);
        }

        $user->save();
    }
}
