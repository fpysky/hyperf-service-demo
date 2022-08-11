<?php

declare(strict_types=1);

namespace App\JsonRpc\TestService;

use Hyperf\RpcServer\Annotation\RpcService;

/** @RpcService(name="TestService", protocol="jsonrpc-http", server="jsonrpc-http", publishTo="consul") */
class TestService implements TestServiceInterface
{
    public function add(int $a, int $b): int
    {
        return $a + $b;
    }
}
