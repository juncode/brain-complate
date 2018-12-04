<?php

$redis = [
    'session' => [
        'host' => '127.0.0.1',
        'port' => 6379,
        'database' => 0,
        'password' => null,
        'persistent' => true,
    ],
];

return append_env_config($redis, 'redis');
