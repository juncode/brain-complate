<?php

namespace V5\Library\Util;

use GuzzleHttp\Client;

class HttpClient
{
    /**
     * @var Client[]
     */
    private static $_clients = [];

    /**
     * @return Client
     */
    public static function common()
    {
        $config = [
            'allow_redirects' => true,
            'http_errors' => false,
            'decode_content' => false,
            'verify' => false,
            'cookies' => false,
        ];

        return self::getter(__FUNCTION__, $config);
    }

    /**
     * @return Client
     */
    private static function getter($key, $config)
    {
        if (isset(self::$_clients[$key])) {
            return self::$_clients[$key];
        }

        $client = new Client($config);
        self::$_clients[$key] = $client;

        return $client;
    }
}
