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
        'nacos' => [
            'host' => env('NACOS_HOST'),
            'port' => (int)env('NACOS_PORT'),
            'username' => env('NACOS_USERNAME'),
            'password' => env('NACOS_PASSWORD'),
            'guzzle' => [
                'config' => null,
            ],
            'group_name' => 'DEFAULT_GROUP',
            'namespace_id' => '9ff3f56f-c407-4471-80dc-da8db255c59a',
            'heartbeat' => 5,
            'ephemeral' => true, // 是否注册临时实例
        ]
    ],
];
