<?php

if (!function_exists('ajax_return')) {
    /**
     * ajax 返回数据.
     *
     * @param $data
     */
    function ajax_return($return)
    {
        ob_end_clean();
        header('Content-Type:application/json; charset=utf-8');
        exit(_json_encode($return));
    }
}

if (!function_exists('_json_encode')) {
    /**
     * 对json_encode的封装
     * 去掉对UNCICDOE的编码,减少中文的字符数.
     *
     * @param $var
     *
     * @return string
     */
    function _json_encode($var)
    {
        return json_encode($var, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

if (!function_exists('_json_decode')) {
    /**
     * 对json_encode的封装
     * 去掉对UNCICDOE的编码,减少中文的字符数.
     *
     * @param $var
     *
     * @return string
     */
    function _json_decode($var)
    {
        return json_decode($var, true);
    }
}

if (!function_exists('send_front_error')) {
    /**
     * @param array  $excCode
     * @param string $appendMsg
     *
     * @throws \V5\Library\Exception\UserVisibleException
     */
    function send_front_error(array $excCode, $appendMsg = '')
    {
        throw new \V5\Library\Exception\UserVisibleException($excCode, $appendMsg);
    }
}

if (!function_exists('rate_select')) {

    /**
     * 从数组中按照概率选择一项.
     *
     * @param array  $items
     * @param string $rateKey
     *
     * @return mixed|null
     */
    function rate_select(array $items, $rateKey = 'rate')
    {
        $items = json_decode(json_encode($items), true);
        $sum = array_sum(array_column($items, $rateKey));

        $count = 0;
        $rateNum = rand(0, $sum);
        $selectItem = null;

        foreach ($items as $item) {
            $count = +$item[$rateKey];
            if ($rateNum <= $count) {
                $selectItem = $item;
                break;
            }
        }

        return $selectItem;
    }
}

if (!function_exists('object_to_array')) {
    /**
     * 将对象转换为数组.
     *
     * @param $var
     *
     * @return mixed
     */
    function object_to_array($var)
    {
        $var = json_decode(json_encode($var), true);

        return $var;
    }
}

if (!function_exists('is_date')) {

    /**
     * 监测给定的字符串是否是日期
     *
     * @param string $var
     * @param string $format
     *
     * @return bool
     */
    function is_date($var, $format = 'Y-m-d')
    {
        return !empty($var) && ($var === date($format, strtotime($var)));
    }
}

if (!function_exists('success_return')) {
    /**
     * @param mixed  $data
     * @param string $info
     *
     * @return array
     */
    function success_return($data = null, $info = null)
    {
        $return = ['code' => 200];
        !is_null($data) && $return['data'] = $data;
        !is_null($info) && $return['info'] = $info;

        return $return;
    }
}

if (!function_exists('common_return')) {
    /**
     * @param null $data
     * @param null $info
     * @return array
     */
    function common_return($data = null, $info = null)
    {
        $return = ['code' => 200];
        !is_null($data) && $return['data'] = $data;
        !is_null($info) && $return['info'] = $info;

        return $return;
    }
}

if (!function_exists('success_json_return')) {
    /**
     * @param mixed  $data
     * @param string $info
     *
     * @return string
     */
    function success_json_return($data = null, $info = null)
    {
        return _json_encode(success_return($data, $info));
    }
}

if (!function_exists('front_encrypt')) {
    /**
     * @param mixed $data
     *
     * @return string
     */
    function front_encrypt($data = null)
    {
        if (is_array($data)) {
            $data = _json_encode($data);
        }

        $encode = base64_encode($data);

        if(strlen($encode) >= 20){
            $pre = substr($encode, 0, 10);

            $split = str_split($pre, 5);

            $encode = $split[1] . $split[0] . substr($encode, 10);
        }

        return $encode;
    }
}

if (!function_exists('get_date_time')) {
    function get_date_time($time = null)
    {
        if (is_null($time)) {
            $time = time();
        }

        return date('Y-m-d H:i:s', $time);
    }
}

if (!function_exists('create_order_id')) {
    /**
     * 生成订单号.
     *
     * @param int $uid 用户uid
     *
     * @return string 返回订单号
     */
    function create_order_id($uid)
    {
        $datetime = date('YmdHis');
        $uid = str_pad($uid, 9, 0, STR_PAD_LEFT);
        $rand = str_pad(mt_rand(1, 99999), 5, 0, STR_PAD_LEFT);

        return $datetime.$uid.$rand;
    }
}
