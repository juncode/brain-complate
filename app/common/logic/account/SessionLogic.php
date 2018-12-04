<?php
/**
 * User: huangjun<j@wonhsi.com>
 * Date: 2018/2/9
 * Time: 20:17.
 */

namespace app\common\logic\account;

use app\common\map\CommonMap;
use app\common\map\ExcCode;
use think\Log;
use think\Session;
use V5\Library\Util\Redis;

class SessionLogic
{
    public function __construct($sessionId)
    {
        if (empty($sessionId)) {
            send_front_error(ExcCode::LOGIN_COOKIE_ERROR);
        }
        config('session.id', $sessionId);
    }

    /**
     * @param $arr
     * @param $prefix string 作用域
     */
    public function set($arr, $prefix)
    {
        Session::init();

        foreach ($arr as $field => $value) {
            Session::set($field, $value, $prefix);
        }
    }

    /**
     * @param string $name
     * @param string $prefix string 作用域
     *
     * @return mixed
     */
    public function get($name = '', $prefix = '')
    {
        Session::init();

        return Session::get($name, $prefix);
    }

    /**
     * @param string $token
     * @param string $prefix
     *
     * @return array
     */
    public function getBySocket($token = '', $prefix = CommonMap::SESSION_USER_INFO)
    {
        $redis = Redis::init('session');

        $data = $redis->get($token);

        $arr = explode('}', $data);

        unset($arr[count($arr) - 1]);

        $arr = array_map(function ($value) {
            if (empty($value)) {
                return '';
            }
            $value .= '}';

            return $value;
        }, $arr);

        $session = [];
        foreach ($arr as $name => $value) {
            $tmp = explode('|', $value);
            $dd = unserialize($tmp[1]);
            foreach ($dd as $key => $dv) {
                if (is_array($dv)) {
                    $tk = key($dv);
                    $session[$tmp[0]][$tk] = $dv[$tk];
                } else {
                    $session[$tmp[0]][$key] = $dv;
                }
            }
        }

        Log::record($session);

        if (!isset($prefix) || !isset($session[$prefix])) {
            return $session;
        }

        if (isset($name)) {
            return $session[$prefix];
        }

        return $session[$prefix];
    }
}
