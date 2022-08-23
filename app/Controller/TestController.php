<?php

declare(strict_types=1);

namespace App\Controller;

use Grpc\Req;
use Grpc\TokenTestResult;

class TestController
{
    public function tokenTest(Req $req): TokenTestResult
    {
        $result = new TokenTestResult();
        $result->setToken($req->getToken());
        return $result;
    }
}
