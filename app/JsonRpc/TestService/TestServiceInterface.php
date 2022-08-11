<?php

declare(strict_types=1);

namespace App\JsonRpc\TestService;

interface TestServiceInterface
{
    public function add(int $a, int $b): int;
}
