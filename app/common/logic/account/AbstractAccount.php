<?php

namespace app\common\logic\account;

use app\common\util\NovelID;
use app\common\map\CommonMap;
use app\common\util\UserID;
use model\OperateHot;
use model\User;
use model\UserCollectNovel;

abstract class AbstractAccount
{
    public $unid = null;
    public $wxUnionID = null;

    public $sessionID = null;

    abstract public static function login();

    protected static function initAccount(User $user)
    {
        $id = $user->id;
        /* 生成nsid */
        $user->unid = (new UserID($id))->toString();
        $user->save();
    }

    protected static function createSessionID($wxappOpenid, $wxappSessionKey)
    {
        $id = substr(md5($wxappSessionKey), 28);
        $id .= substr(md5($wxappOpenid), 28);

        return md5($id.CommonMap::SESSION_CREATE_SALT.uniqid(mt_rand(), true));
    }
}
