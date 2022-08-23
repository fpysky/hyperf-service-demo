<?php

declare(strict_types=1);

namespace App\Controller;

use Grpc\HiReply;
use Grpc\HiUser;

class HiController
{
    public function sayHello(HiUser $user): HiReply
    {
        $hiReply = new HiReply();
        $hiReply->setMessage('Hello World');
        $hiReply->setUser($user);
        return $hiReply;
    }
}
