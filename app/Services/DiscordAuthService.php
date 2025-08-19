<?php

namespace App\Services;

use App\Repositories\DiscordAuthRepository;

class DiscordAuthService
{
    protected $repository;

    public function __construct(DiscordAuthRepository $repository)
    {
        $this->repository = $repository;
    }

    // Métodos do service
}