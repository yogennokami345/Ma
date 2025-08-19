<?php

namespace App\Http\Controllers;

use App\Services\ShoppingService;
use App\Utils\Settings;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShoppingController extends Controller
{
    protected $service;

    public function __construct(ShoppingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $plans = $this->service->getPlans();

        return Inertia::render('Shopping/Show', [
            'settings' => Settings::get(),
            'plans' => $plans,
        ]);
    }

    public function pay($id)
    {
        $user = auth()->user();

        $data['user_id'] = $user->id;
        $data['plan_id'] = $id;
        $plan = $this->service->getPlan($id);
        $data['amount'] = $plan->price;

        $url = $this->service->createOrder($data);

        return Inertia::location($url);
    }

    public function callBackPay($id)
    {

        try {
            $this->service->verifyPayment($id);
            $paid = true;
        } catch (\Throwable $th) {
            $paid = false;
        }

        return Inertia::render('Shopping/Callback', [
            'settings' => Settings::get(),
            'paid' => $paid,
        ]);
    }

}
