<?php

namespace V5\Library\Util;

class Redis
{
    private static $_redis = [];

    /**
     * 初始化redis链接实例.
     *
     * @param string     $configIndx 配置文件中的key
     * @param int|string $db
     *
     * @return \Redis
     */
    public static function init($configIndx, $db = 0)
    {
        $index = $configIndx . '-' . $db;

        if (isset(self::$_redis[$index])) {
            return self::$_redis[$index];
        }

        $config = config('redis.' . $configIndx);

        $redis = new \Redis();
        if ($config['persistent']) {
            $redis->pconnect($config['host'], $config['port']);
        } else {
            $redis->connect($config['host'], $config['port']);
        }

        if (!empty($config['password'])) {
            $redis->auth($config['password']);
        }

        $db = isset($config['dbs'][$db]) ? (int) $config['dbs'][$db] : 0;
        if ($db !== 0) {
            $redis->select($db);
        }

        self::$_redis[$index] = $redis;

        return $redis;
    }
}
