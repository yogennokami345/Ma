<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends AbstractRepository
{
    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function findByPaymentId($paymentId)
    {
        return $this->model->with('plan', 'user', 'plan.role')->where('payment_id', $paymentId)->first();
    }
}