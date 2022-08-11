<?php

declare(strict_types=1);

return [
    'enable' => [
        // 开启服务发现
        'discovery' => true,
        // 开启服务注册
        'register' => true,
    ],
    // 服务提供者相关配置
    'providers' => [],
    // 服务驱动相关配置
    'drivers' => [
        'consul' => [
            'uri' => env('CONSUL_URI', 'http://127.0.0.1:8500'),
            'token' => '',
            'check' => [
                'deregister_critical_service_after' => '90m',
                'interval' => '1s',
            ],
        ],
    ],
];
