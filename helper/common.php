<?php

/**
 * 生产环境 将错误显示级别降低为 E_ERROR | E_PARSE
 */
if (APP_ENV === APP_ENV_PRODUCTION) {
    error_reporting(E_ERROR);
}

if (BIND_MODULE === 'wap') {
    $origin = empty($_SERVER['HTTP_ORIGIN']) ? '' : $_SERVER['HTTP_ORIGIN'];

    if (!empty($origin)) {
        header("Access-Control-Allow-Origin: {$origin}");
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Credentials: true');
    }
}


if (!function_exists('get_client_ip')) {

    /**
     * 获取客户端IP地址
     *
     * @param int  $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param bool $adv  是否进行高级模式获取（有可能被伪装）
     *
     * @return mixed
     */
    function get_client_ip($type = 0, $adv = true)
    {
        $type = $type ? 1 : 0;
        static $ip = null;
        if ($ip !== null) {
            return $ip[$type];
        }
        if ($adv) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown', $arr);
                if (false !== $pos) {
                    unset($arr[$pos]);
                }
                $ip = trim($arr[0]);
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf('%u', ip2long($ip));
        $ip = $long ? [$ip, $long] : ['0.0.0.0', 0];

        return $ip[$type];
    }
}