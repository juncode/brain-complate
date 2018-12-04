<?php

namespace V5\Library\Util;

class HttpHeader
{
    /**
     * 缓存时间
     *
     * @param number $seconds 缓存的秒数
     */
    public static function cacheControl($seconds = null)
    {
        $seconds = is_null($seconds) ? config('cdn.cache-control') : $seconds;

        header('Cache-Control: max-age=' . $seconds);
    }

    /**
     * 发送cors头
     *
     * @return bool
     */
    public static function cors()
    {
        $origin = request()->server('HTTP_ORIGIN', '');
        if (empty($origin)) {
            return false;
        }

        $allowOrigins = config('domain.cors');
        foreach ($allowOrigins as $allowOrigin) {
            /* 支持配置域名及其所有子域 */
            if (preg_match("#(.)*{$allowOrigin}$#", $origin)) {
                header('Access-Control-Allow-Origin: ' . $origin);

                return true;
            }
        }

        return false;
    }
}
