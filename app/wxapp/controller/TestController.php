<?php

namespace app\wxapp\controller;

use app\common\logic\account\SessionLogic;
use app\common\map\CommonMap;
use app\common\util\UserID;
use think\Session;

class TestController
{
    public function getUsid()
    {
        $number = request()->get('id');
        $id = new UserID($number);

        echo $id->toString();
    }

    public function getFakeLogin()
    {
        $unid = 3;

        $sessionID = substr(md5('l2122ing3'), 28);
        $sessionID .= substr(md5('l2122ing3'), 28);

        $sessionID = md5($sessionID.CommonMap::SESSION_CREATE_SALT);
        config('session.id', $sessionID);
        $session = (new SessionLogic($sessionID));
        $session->set(['unid' =>$unid, ['wxappOpenid' => 'xxxx1234xxxx'],  ['wxappOpenid2' => 'xxxx1234xxxx']], CommonMap::SESSION_USER_INFO);

        return $sessionID;
    }

    public function getSession()
    {
        $session = request()->get('sid');

        $data = (new SessionLogic($session))->getBySocket();
        var_dump($data);exit;

        $arr = explode('}}', $data);

        unset($arr[count($arr) - 1]);

        $arr = array_map(function($value){
            if(empty($value)){
                return '';
            }
            $value .= '}}';
            return $value;
        }, $arr);

        $session = [];
        foreach ($arr as $name => $value){
            $tmp = explode('|', $value);
            $dd = unserialize($tmp[1]);
            foreach ($dd as $key => $dv){
                if(is_array($dv)){
                    $tk = key($dv);
                    $session[$tmp[0]][$tk] = $dv[$tk];
                } else {
                    $session[$tmp[0]][$key] = $dv;
                }
            }
        }
    }
}
