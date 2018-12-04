<?php

namespace app\common\logic\account;

use app\common\map\ExcCode;
use app\common\map\CommonMap;
use app\common\util\WechatHelper;
use model\User;
use think\Exception;
use think\Log;

class WxAppAccount extends AbstractAccount
{
    public $wxappOpenid = null;
    public $wxappSessionKey = null;

    private static $hiddenField = 'id,wx_open_id,wx_union_id,add_time';

    public function __construct($sessionID = null)
    {
        if (!is_null($sessionID)) {
            $session = (new SessionLogic($sessionID))->get('', CommonMap::SESSION_USER_INFO);

            if (empty($session) || empty($session)) {
                send_front_error(ExcCode::LOGIN_TIME_OUT);
            }

            $this->sessionID = $sessionID;
            $this->unid = $session['unid'];
            $this->wxappOpenid = $session['wxappOpenid'];
            $this->wxappSessionKey = $session['wxappSessionKey'];
        }
    }

    /**
     * 通过小程序的code执行登录.
     *
     * @param string $code
     *
     * @return WxAppAccount|bool
     *
     * @throws \V5\Library\Exception\UserVisibleException
     */
    public static function login($code = null)
    {
        try {
            /* 通过客户端传来的code,从微信服务器获得openid */
            $wxInfo = WechatHelper::wxapp()->sns->getSessionKey($code);
        } catch (Exception $e) {
            send_front_error(ExcCode::LOGIN_WECHAT_LOGIN_FAIL);

            return false;
        }

        Log::record("微信登录获取用户信息");
        Log::record(_json_encode($wxInfo));

        $wxappOpenid = $wxInfo['openid'];
        $wxappSessionKey = $wxInfo['session_key'];
        $wxUnionId = $wxInfo['unionid'];

        /* 通过openid查找用户 */
        $user = User::getByWxapp($wxappOpenid, $wxUnionId);

        /* 如果是新用户 */
        if (empty($user)) {
            $user = new User();

            $user->wx_open_id = $wxappOpenid;
            $user->wx_union_id = $wxUnionId;

            $user->save();

            /* 新用户初始化信息 */
            self::initAccount($user);
        } else {
            /* 更新用户最近登录时间 */
            $user->update_time = get_date_time();

            /* 老用户增加union_id更新 */
            if (!empty($wxUnionId)) {
                $user->wx_union_id = $wxUnionId;
            }

            $user->save();
        }

        /* 创建srd_session */
        $sessionID = self::createSessionID($wxappOpenid, $wxappSessionKey);

        (new SessionLogic($sessionID))->set([
            'unid' => $user->unid,
            'wxappOpenid' => $wxappOpenid,
            'wxappSessionKey' => $wxappSessionKey,
            'wxUnionId' => $wxUnionId,
        ], CommonMap::SESSION_USER_INFO);

        $account = new self();

        $account->sessionID = $sessionID;
        $account->unid = $user->unid;
        $account->wxappOpenid = $wxappOpenid;
        $account->wxappSessionKey = $wxappSessionKey;
        $account->wxUnionID = $wxUnionId;

        return $account;
    }

    public static function updateInfo($unid, $avatar, $nickname, $gender, $city, $province, $country)
    {
        $user = User::getByUnid($unid);

        if (empty($user)) {
            send_front_error(ExcCode::USER_NOT_EXIST);
        }

        $user->wx_avatar_url = $avatar;
        $user->wx_nick_name = $nickname;
        $user->wx_gender = $gender;
        $user->wx_country = $country;
        $user->wx_province = $province;
        $user->wx_city = $city;

        $user->save();
    }

    /**
     * 获取用户信息.
     *
     * @param $unid
     *
     * @return mixed
     */
    public static function getUserInfo($unid)
    {
        $user = User::getByUnid($unid);

        if (empty($user)) {
            send_front_error(ExcCode::USER_NOT_EXIST);
        }

        $info = $user->toArray();

        return self::hiddenField($info);
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    private static function hiddenField($data)
    {
        $result = [];

        $hidden = explode(',', self::$hiddenField);
        foreach ($data as $field => $value) {
            if (in_array($field, $hidden)) {
                continue;
            }
            $field = str_replace('wx_', '', $field);
            $result[$field] = $value;
        }

        return $result;
    }
}
