<?php

namespace App\Repositories;

use App\Models\Plan;

class ShoppingRepository extends AbstractRepository
{
    protected $model;

    public function __construct(Plan $model)
    {
        $this->model = $model;
    }
}